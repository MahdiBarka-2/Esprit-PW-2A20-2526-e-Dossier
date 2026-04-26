/**
 * InsightHub - Professional Form Validation Engine
 * Strictly follows the constraint: No HTML5 validation attributes used.
 * Uses SweetAlert2 for feedback.
 */

document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        // Disable HTML5 validation just in case
        form.setAttribute('novalidate', '');

        form.addEventListener('submit', (e) => {
            // Skip validation for search and filter forms (GET requests)
            if (form.method.toUpperCase() === 'GET') return;

            let errors = [];
            const formData = new FormData(form);

            // Validation Logic
            for (let [name, value] of formData.entries()) {
                const element = form.querySelector(`[name="${name}"]`);
                if (!element) continue;

                const label = form.querySelector(`label[for="${element.id}"]`)?.innerText || name;
                
                // Rule: Required Check (Simulated)
                if (value.trim() === '') {
                    errors.push(`${label} is mandatory.`);
                }

                // Rule: Length checks
                if (name === 'titre' && value.length < 3) {
                    errors.push(`Title must be at least 3 characters.`);
                }
                if (name === 'contenu' && value.length < 5) {
                    errors.push(`Content is too short.`);
                }

                // Rule: Alpha only for author
                if (name === 'auteur' && !/^[a-zA-Z\s]+$/.test(value)) {
                    errors.push(`Author name must contain only letters and spaces.`);
                }
            }

            if (errors.length > 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Failed',
                    html: `<ul style="text-align: left;">${errors.map(err => `<li>${err}</li>`).join('')}</ul>`,
                    background: '#111827',
                    color: '#F3F4F6',
                    confirmButtonColor: '#10B981',
                    customClass: {
                        popup: 'rounded-4 shadow-lg border border-opacity-10 border-white'
                    }
                });
            }
        });
    });
});
