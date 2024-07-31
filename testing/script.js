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
    const radioButtons = document.querySelectorAll('input[type="radio"]');
    const labels = document.querySelectorAll('.radio-buttons .radio-label');
    
    radioButtons.forEach(radio => {
        radio.addEventListener('mouseenter', (event) => {
            event.target.checked = true; // Check the radio button on hover
            changeImage(event.target.value);
        });
    });

    labels.forEach(label => {
        label.addEventListener('mouseenter', (event) => {
            const associatedRadio = document.getElementById(label.getAttribute('for'));
            associatedRadio.checked = true; // Check the radio button
            changeImage(associatedRadio.value);
        });
    });
});

function changeImage(painType) {
    const bodyImage = document.getElementById('bodyImage');
    switch (painType) {
        case 'neck':
            bodyImage.src = '../resources/img/services/service-neck.png'; // Change to the correct path for the neck image
            break;
        case 'shoulder':
            bodyImage.src = '../resources/img/services/service-shoulder.png'; // Change to the correct path for the shoulder image
            break;
        case 'knee':
            bodyImage.src = '../resources/img/services/service-knee.png'; // Change to the correct path for the knee image
            break;
        case 'hand':
            bodyImage.src = '../resources/img/services/service-hand.png'; // Change to the correct path for the hand image
            break;
        case 'foot':
            bodyImage.src = '../resources/img/services/service-foot.png'; // Change to the correct path for the foot image
            break;
        default:
            bodyImage.src = '../resources/img/services/service-neck.png'; // Change to a default image if necessary
            break;
    }
}

