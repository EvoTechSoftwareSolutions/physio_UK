// function changeImage(painType) {
//     const bodyImage = document.getElementById('bodyImage');
//     switch (painType) {
//         case 'neck':
//             bodyImage.src = '../resources/img/services/service-neck.png';
//             break;
//         case 'shoulder':
//             bodyImage.src = '../resources/img/services/service-shoulder.png';
//             break;
//         case 'knee':
//             bodyImage.src = '../resources/img/services/service-knee.png';
//             break;
//         case 'hand':
//             bodyImage.src = '../resources/img/services/service-hand.png';
//             break;
//         case 'foot':
//             bodyImage.src = '../resources/img/services/service-foot.png';
//             break;
//         default:
//             bodyImage.src = '../resources/img/services/service-neck.png';
//             break;
//     }
// }


document.addEventListener('DOMContentLoaded', () => {
    const frontRadioButtons = document.querySelectorAll('input[name="frontPain"]');
    const backRadioButtons = document.querySelectorAll('input[name="backPain"]');
    const frontLabels = document.querySelectorAll('input[name="frontPain"] + .radio-label');
    const backLabels = document.querySelectorAll('input[name="backPain"] + .radio-label');
    
    // Set initial hover effect on the first radio buttons
    const firstFrontRadio = document.getElementById('neck');
    simulateHover(firstFrontRadio);
    changeImage(firstFrontRadio.value, 'front');

    const firstBackRadio = document.getElementById('hip');
    simulateHover(firstBackRadio);
    changeImage(firstBackRadio.value, 'back');

    frontRadioButtons.forEach(radio => {
        radio.addEventListener('mouseenter', (event) => {
            clearHover('front');
            simulateHover(event.target);
            changeImage(event.target.value, 'front');
        });
    });

    backRadioButtons.forEach(radio => {
        radio.addEventListener('mouseenter', (event) => {
            clearHover('back');
            simulateHover(event.target);
            changeImage(event.target.value, 'back');
        });
    });

    frontLabels.forEach(label => {
        label.addEventListener('mouseenter', (event) => {
            clearHover('front');
            const associatedRadio = document.getElementById(label.getAttribute('for'));
            simulateHover(associatedRadio);
            changeImage(associatedRadio.value, 'front');
        });
    });

    backLabels.forEach(label => {
        label.addEventListener('mouseenter', (event) => {
            clearHover('back');
            const associatedRadio = document.getElementById(label.getAttribute('for'));
            simulateHover(associatedRadio);
            changeImage(associatedRadio.value, 'back');
        });
    });
});

function simulateHover(radio) {
    const label = radio.nextElementSibling;
    label.classList.add('hover');
}

function clearHover(section) {
    const labels = document.querySelectorAll(`input[name="${section}Pain"] + .radio-label`);
    labels.forEach(label => {
        label.classList.remove('hover');
    });
}

function changeImage(painType, section) {
    const image = document.getElementById(section === 'front' ? 'frontImage' : 'backImage');
    switch (painType) {
        case 'neck':
            image.src = '../resources/img/services/service-neck.png'; // Change to the correct path for the neck image
            break;
        case 'shoulder':
            image.src = '../resources/img/services/service-shoulder.png'; // Change to the correct path for the shoulder image
            break;
        case 'knee':
            image.src = '../resources/img/services/service-knee.png'; // Change to the correct path for the knee image
            break;
        case 'hand':
            image.src = '../resources/img/services/service-hand.png'; // Change to the correct path for the hand image
            break;
        case 'foot':
            image.src = '../resources/img/services/service-foot.png'; // Change to the correct path for the foot image
            break;
        case 'hip':
            image.src = '../resources/img/services/service-hip.png'; // Change to the correct path for the hip image
            break;
        case 'elbow':
            image.src = '../resources/img/services/service-elbow.png'; // Change to the correct path for the elbow image
            break;
        case 'tricep':
            image.src = '../resources/img/services/service-tricep.png'; // Change to the correct path for the tricep image
            break;
        case 'ankle':
            image.src = '../resources/img/services/service-ankle.png'; // Change to the correct path for the ankle image
            break;
        case 'back':
            image.src = '../resources/img/services/service-back.png'; // Change to the correct path for the back image
            break;
        default:
            frontImage.src = '../resources/img/services/service-neck.png'; // Change to a default image if necessary
            backImage.src = '../resources/img/services/service-hip.png'; // Change to a default image if necessary
            break;
    }
}


