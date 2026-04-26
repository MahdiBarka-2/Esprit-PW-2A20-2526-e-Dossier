function validateForm() {
    const titre    = document.getElementById("f-titre").value.trim();
    const dateDebut = document.getElementById("f-datedeb").value;
    const dateFin   = document.getElementById("f-datefin").value;
    const capacite  = document.getElementById("f-cap").value;
    const lieu      = document.getElementById("f-lieu").value.trim();

    let errors = [];

    if (titre === "")
        errors.push("Le titre est obligatoire.");

    if (dateDebut === "")
        errors.push("La date de début est obligatoire.");

    if (dateFin !== "" && dateFin < dateDebut)
        errors.push("La date de fin doit être après la date de début.");

    if (capacite === "" || parseInt(capacite) <= 0)
        errors.push("Le nombre de participants doit être supérieur à 0.");

    if (lieu === "")
        errors.push("Le lieu est obligatoire.");

    if (errors.length > 0) {
        showValidationError(errors);
        return false;
    }

    return true;
}

function showValidationError(errors) {
    // Remove existing toast if any
    const existing = document.getElementById("validation-toast");
    if (existing) existing.remove();

    // Build toast
    const toast = document.createElement("div");
    toast.id = "validation-toast";
    toast.innerHTML = `
        <div style="
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 99999;
            min-width: 300px;
            max-width: 400px;
            background: #fff;
            border: 1px solid #fecaca;
            border-left: 4px solid #dc2626;
            border-radius: 10px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.13);
            padding: 16px 18px 14px;
            animation: slideInRight .3s cubic-bezier(.16,1,.3,1);
        ">
            <div style="display:flex;align-items:flex-start;gap:10px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                     fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     style="flex-shrink:0;margin-top:1px;">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <div style="flex:1;">
                    <div style="font-weight:700;font-size:14px;color:#991b1b;margin-bottom:6px;">
                        Veuillez corriger les erreurs suivantes
                    </div>
                    <ul style="margin:0;padding-left:16px;font-size:13px;color:#374151;">
                        ${errors.map(e => `<li style="margin-bottom:3px;">${e}</li>`).join('')}
                    </ul>
                </div>
                <button onclick="document.getElementById('validation-toast').remove()"
                        style="background:none;border:none;cursor:pointer;color:#9ca3af;font-size:18px;line-height:1;padding:0;margin-left:4px;">
                    ×
                </button>
            </div>
        </div>
        <style>
            @keyframes slideInRight {
                from { opacity:0; transform:translateX(40px); }
                to   { opacity:1; transform:translateX(0); }
            }
        </style>
    `;

    document.body.appendChild(toast);

    // Auto-dismiss after 5s
    setTimeout(() => {
        const el = document.getElementById("validation-toast");
        if (el) {
            el.style.transition = "opacity .4s, transform .4s";
            el.style.opacity = "0";
            el.style.transform = "translateX(40px)";
            setTimeout(() => el.remove(), 400);
        }
    }, 5000);
}
