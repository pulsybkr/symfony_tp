// assets/app.js
import './styles/app.css';

// Pour les icônes Feather
import feather from 'feather-icons';

document.addEventListener('DOMContentLoaded', () => {
    // Initialiser les icônes Feather
    feather.replace();
    
    // Toggle du menu mobile
    const mobileMenuBtn = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }
    
    // Gestion du formulaire dynamique pour les ingrédients et étapes
    const addIngredientBtn = document.getElementById('add-ingredient');
    const ingredientsContainer = document.getElementById('ingredients-container');
    
    if (addIngredientBtn && ingredientsContainer) {
        addIngredientBtn.addEventListener('click', () => {
            const prototype = ingredientsContainer.dataset.prototype;
            const index = ingredientsContainer.dataset.index;
            const newForm = prototype.replace(/__name__/g, index);
            ingredientsContainer.dataset.index = parseInt(index) + 1;
            
            const div = document.createElement('div');
            div.innerHTML = newForm;
            div.classList.add('ingredient-item', 'flex', 'items-center', 'gap-2', 'mb-2');
            
            const deleteBtn = document.createElement('button');
            deleteBtn.innerHTML = '<i data-feather="trash-2"></i>';
            deleteBtn.classList.add('text-red-500');
            deleteBtn.addEventListener('click', () => div.remove());
            
            div.appendChild(deleteBtn);
            ingredientsContainer.appendChild(div);
            
            feather.replace();
        });
    }

    // Similaire pour les étapes
    const addStepBtn = document.getElementById('add-step');
    const stepsContainer = document.getElementById('steps-container');
    
    if (addStepBtn && stepsContainer) {
        // Code similaire pour les étapes
    }
    
    // Preview des images lors du téléchargement
    const imageInputs = document.querySelectorAll('.image-upload');
    
    imageInputs.forEach(input => {
        input.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                const preview = document.getElementById(`${input.id}-preview`);
                
                reader.onload = (e) => {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                
                reader.readAsDataURL(file);
            }
        });
    });
});