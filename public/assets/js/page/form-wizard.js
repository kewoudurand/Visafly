'use strict';
$(function () {
    var form = $('#wizard_with_validation').show();

    // ✅ Fonction de mise à jour de la barre de progression personnalisée
    function updateProgress(currentIndex) {
        var totalSteps = 4;

        for (var i = 1; i <= totalSteps; i++) {
            var circle    = document.getElementById('step-circle-' + i);
            var indicator = document.getElementById('step-indicator-' + i);

            if (!circle || !indicator) continue;

            circle.classList.remove('active', 'completed');
            indicator.classList.remove('active', 'completed');

            if (i < currentIndex + 1) {
                // ✅ Étapes passées → coche verte
                circle.classList.add('completed');
                circle.textContent = '✓';
                indicator.classList.add('completed');
            } else if (i === currentIndex + 1) {
                // ✅ Étape actuelle → bleu
                circle.classList.add('active');
                circle.textContent = i;
                indicator.classList.add('active');
            } else {
                // Étapes futures → gris
                circle.textContent = i;
            }
        }

        // ✅ Mise à jour de la ligne de progression
        var percent = (currentIndex / (totalSteps - 1)) * 90;
        var line = document.getElementById('wizardProgressLine');
        var text = document.getElementById('currentStepText');
        if (line) line.style.width = percent + '%';
        if (text) text.textContent  = currentIndex + 1;
    }

    form.steps({
        headerTag: 'h3',
        bodyTag: 'fieldset',
        transitionEffect: 'slideLeft',

        onInit: function (event, currentIndex) {
            var $tab = $(event.currentTarget).find('ul[role="tablist"] li');
            $tab.css('width', (100 / $tab.length) + '%');
            setButtonWavesEffect(event);

            // ✅ Initialiser la barre à l'étape 1
            updateProgress(currentIndex);
        },

        onStepChanging: function (event, currentIndex, newIndex) {
            if (currentIndex > newIndex) return true;
            if (currentIndex < newIndex) {
                form.find('.body:eq(' + newIndex + ') label.error').remove();
                form.find('.body:eq(' + newIndex + ') .error').removeClass('error');
            }
            form.validate().settings.ignore = ':disabled,:hidden';
            return form.valid();
        },

        onStepChanged: function (event, currentIndex, priorIndex) {
            setButtonWavesEffect(event);
            // ✅ Mettre à jour la barre à chaque changement d'étape
            updateProgress(currentIndex);
        },

        onFinishing: function (event, currentIndex) {
            form.validate().settings.ignore = ':disabled';
            return form.valid();
        },

        onFinished: function (event, currentIndex) {
            event.preventDefault();
            $('#confirmModal').modal('show');
        }
    });

    form.validate({
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
        },
        rules: {
            'confirm': { equalTo: '#password' }
        }
    });
});

// ✅ Vrai submit natif quand on clique sur "D'accord"
$('#confirmSubmit').on('click', function () {
    console.log("CLICK OK");
    document.getElementById('realSubmit').click();
});

function setButtonWavesEffect(event) {
    $(event.currentTarget)
        .find('[role="menu"] li a')
        .removeClass('waves-effect')
        .not('.disabled')
        .addClass('waves-effect');
}