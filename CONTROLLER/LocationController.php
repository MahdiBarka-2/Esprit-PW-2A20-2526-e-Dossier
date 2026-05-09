<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__) . '/MODEL/Database.php';

// Only set JSON header if this is an AJAX request
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    handleLocationAction($_GET['action']);
    exit;
}

function handleLocationAction($action) {
    $db = new Database();
    $conn = $db->getConnection();
    
    if ($action === 'update') {
        // Save location from agent's phone
        $input = json_decode(file_get_contents('php://input'), true);
        $agent_id = $input['agent_id'] ?? null;
        $lat = $input['latitude'] ?? null;
        $lng = $input['longitude'] ?? null;

        if ($agent_id && $lat && $lng) {
            $stmt = $conn->prepare("INSERT INTO agent_locations (agent_id, latitude, longitude) VALUES (:aid, :lat, :lng)");
            $stmt->execute(['aid' => $agent_id, 'lat' => $lat, 'lng' => $lng]);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Missing data']);
        }
    } elseif ($action === 'fetch') {
        // Fetch locations for admin map
        $agent_id = $_GET['agent_id'] ?? null;
        if ($agent_id) {
            // Get last 20 positions for the trace
            $stmt = $conn->prepare("SELECT latitude, longitude, timestamp FROM agent_locations WHERE agent_id = :aid ORDER BY timestamp DESC LIMIT 20");
            $stmt->execute(['aid' => $agent_id]);
            $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'data' => array_reverse($locations)]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No agent ID']);
        }
    }
}

/**
 * Premium GPS Tracking UI Component
 */
function renderGPSTracker() {
    ob_start();
    ?>
    <!-- Leaflet CSS & JS for Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- GPS Tracker Styles -->
    <style>
        #gps-tracker-toggle {
            position: fixed;
            bottom: 30px;
            left: 30px;
            z-index: 10000;
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(0, 198, 255, 0.4);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 4px solid white;
            font-size: 1.6rem;
            animation: gps-float 4s ease-in-out infinite;
        }

        @keyframes gps-float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(5deg); }
        }

        #gps-tracker-toggle:hover {
            transform: scale(1.1) rotate(15deg);
            box-shadow: 0 12px 25px rgba(0, 198, 255, 0.6);
        }

        #gps-panel {
            position: fixed;
            top: 20px;
            left: 20px;
            width: 400px;
            height: calc(100vh - 40px);
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(15px);
            z-index: 20001;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 15px 0 50px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transform: translateX(-120%);
            transition: transform 0.6s cubic-bezier(0.19, 1, 0.22, 1);
        }

        #gps-panel.open {
            transform: translateX(0);
        }

        .gps-header {
            padding: 25px;
            background: rgba(255, 255, 255, 0.03);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .gps-search-wrapper {
            position: relative;
            margin-top: 15px;
        }

        .gps-search-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 12px 15px 12px 45px;
            color: white;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .gps-search-input:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: #00c6ff;
            outline: none;
            box-shadow: 0 0 15px rgba(0, 198, 255, 0.2);
        }

        .gps-search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.4);
        }

        #gps-map-container {
            flex: 1;
            width: 100%;
            position: relative;
            background: #000;
            margin-top: 15px; /* Moved map down */
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        #gps-live-map {
            height: 100%;
            width: 100%;
        }

        .agent-results {
            max-height: 200px;
            overflow-y: auto;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 0 0 12px 12px;
            display: none;
        }

        .agent-item {
            padding: 12px 20px;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .agent-item:hover {
            background: rgba(0, 198, 255, 0.15);
        }

        .agent-item img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            margin-right: 12px;
            object-fit: cover;
        }

        .gps-footer {
            padding: 15px 25px;
            background: rgba(0, 0, 0, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 20px;
            background: rgba(0, 255, 0, 0.1);
            color: #4ade80;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .pulse {
            width: 8px;
            height: 8px;
            background: #4ade80;
            border-radius: 50%;
            box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.7);
            animation: pulse-ring 1.5s infinite;
        }

        @keyframes pulse-ring {
            0% { box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(74, 222, 128, 0); }
            100% { box-shadow: 0 0 0 0 rgba(74, 222, 128, 0); }
        }
    </style>

    <!-- Floating Toggle Button -->
    <div id="gps-tracker-toggle" onclick="toggleGPSPanel()" title="Real-Time GPS Tracker">
        <i class="fas fa-map-marked-alt"></i>
    </div>

    <!-- The Left GPS Panel -->
    <div id="gps-panel">
        <div class="gps-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="text-white mb-0" style="font-weight: 700; letter-spacing: -0.5px;">
                    <i class="fas fa-satellite me-2 text-info"></i>E-Dossier GPS
                </h5>
                <button class="btn-close btn-close-white btn-sm" onclick="toggleGPSPanel()"></button>
            </div>
            
            <div class="gps-search-wrapper">
                <i class="fas fa-search gps-search-icon"></i>
                <input type="text" id="gps-search-input" class="gps-search-input" placeholder="Search agent name..." oninput="searchAgentsGPS(this.value)">
                <div id="gps-results" class="agent-results"></div>
            </div>
        </div>

        <div id="gps-map-container">
            <div id="gps-live-map"></div>
            <div id="gps-overlay-info" style="position: absolute; top: 15px; right: 15px; z-index: 1000; background: rgba(0,0,0,0.6); padding: 8px 12px; border-radius: 10px; color: white; font-size: 0.8rem; display: none;">
                Tracking: <span id="tracking-name-overlay" class="fw-bold"></span>
            </div>
        </div>

        <div class="gps-footer">
            <div class="status-badge">
                <div class="pulse"></div> System Live (0.1s)
            </div>
            <button class="btn btn-link btn-sm text-white-50 p-0 text-decoration-none" onclick="toggleGPSPanel()">
                <i class="fas fa-compress-alt me-1"></i> Hide
            </button>
        </div>
    </div>

    <script>
        let gpsMap;
        let gpsMarker;
        let gpsPolyline;
        let gpsTrackingInterval;
        let currentTrackingId = null;

        function toggleGPSPanel() {
            const panel = document.getElementById('gps-panel');
            panel.classList.toggle('open');
            
            if (panel.classList.contains('open')) {
                setTimeout(() => {
                    if (!gpsMap) {
                        gpsMap = L.map('gps-live-map', {
                            zoomControl: false,
                            attributionControl: false
                        }).setView([33.8869, 9.5375], 6);
                        
                        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                            maxZoom: 20
                        }).addTo(gpsMap);

                        L.control.zoom({ position: 'bottomright' }).addTo(gpsMap);
                    } else {
                        gpsMap.invalidateSize();
                    }
                }, 400);
            }
        }

        function searchAgentsGPS(query) {
            const resultsDiv = document.getElementById('gps-results');
            if (query.length < 2) {
                resultsDiv.style.display = 'none';
                return;
            }

            // Fetch agents from the existing CONTROLLER endpoint
            fetch(`../../CONTROLLER/UserCONTROLLER.php?action=search&query=${query}`)
                .then(r => r.json())
                .then(data => {
                    resultsDiv.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(agent => {
                            const item = document.createElement('div');
                            item.className = 'agent-item';
                            const img = agent.profile_image_url || '../../assets/images/avatar/default.jpg';
                            item.innerHTML = `
                                <img src="${img}">
                                <div class="text-white">
                                    <div class="fw-bold small">${agent.name}</div>
                                    <div style="font-size: 0.7rem; opacity: 0.6;">${agent.role}</div>
                                </div>
                            `;
                            item.onclick = () => selectAgentForTracking(agent);
                            resultsDiv.appendChild(item);
                        });
                        resultsDiv.style.display = 'block';
                    } else {
                        resultsDiv.style.display = 'none';
                    }
                });
        }

        function selectAgentForTracking(agent) {
            document.getElementById('gps-results').style.display = 'none';
            document.getElementById('gps-search-input').value = agent.name;
            document.getElementById('gps-overlay-info').style.display = 'block';
            document.getElementById('tracking-name-overlay').innerText = agent.name;
            
            if (gpsTrackingInterval) clearInterval(gpsTrackingInterval);
            
            currentTrackingId = agent.id;
            
            // Ensure panel is open
            const panel = document.getElementById('gps-panel');
            if (!panel.classList.contains('open')) toggleGPSPanel();

            // Clear previous map layers
            if (gpsMarker) gpsMarker.remove();
            if (gpsPolyline) gpsPolyline.remove();
            gpsMarker = null;
            gpsPolyline = null;

            fetchGPSLocation(agent.id);
            gpsTrackingInterval = setInterval(() => fetchGPSLocation(agent.id), 100);
        }

        // Bridge function for old table buttons
        function openTracker(id, name) {
            selectAgentForTracking({id: id, name: name});
        }

        function fetchGPSLocation(agentId) {
            fetch(`../../CONTROLLER/LocationCONTROLLER.php?action=fetch&agent_id=${agentId}`)
                .then(r => r.json())
                .then(res => {
                    if (res.status === 'success' && res.data.length > 0) {
                        const path = res.data.map(loc => [parseFloat(loc.latitude), parseFloat(loc.longitude)]);
                        const lastPos = path[path.length - 1];

                        if (!gpsMarker) {
                            gpsMarker = L.marker(lastPos, {
                                icon: L.divIcon({
                                    className: 'custom-gps-marker',
                                    html: '<div style="width: 20px; height: 20px; background: #00c6ff; border: 3px solid white; border-radius: 50%; box-shadow: 0 0 15px rgba(0, 198, 255, 0.8);"></div>',
                                    iconSize: [20, 20],
                                    iconAnchor: [10, 10]
                                })
                            }).addTo(gpsMap);
                        } else {
                            gpsMarker.setLatLng(lastPos);
                        }

                        if (!gpsPolyline) {
                            gpsPolyline = L.polyline(path, {
                                color: '#007bff',
                                weight: 5,
                                opacity: 1,
                                lineJoin: 'round'
                            }).addTo(gpsMap);
                        } else {
                            gpsPolyline.setLatLngs(path);
                        }

                        gpsMap.panTo(lastPos, { animate: true, duration: 0.1 });
                    }
                });
        }
    </script>
    <?php
    return ob_get_clean();
}
