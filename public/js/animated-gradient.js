document.addEventListener('DOMContentLoaded', function() {

    function randomHslColor() {

        // hue
        let startHue = 0;
        let endHue = 360;
        let hue = Math.floor(Math.random() * (endHue - startHue + 1)) + startHue; // Random hue value between startHue and endHue

        // saturation
        let saturation = 75;

        // lightness
        let lightness = 80;

        return 'hsl(' + hue + ', ' + saturation + '%, ' + lightness + '%)';
    }

    let gradients = document.querySelectorAll('.animated-gradient-js');

    for (let i = 0; i < gradients.length; i++) {

        let possibleAngles = [-45, -30, 30, 45];
        let randomIndex = Math.floor(Math.random() * possibleAngles.length);
        let startColor, endColor, angle;

        do {
            startColor = randomHslColor();
            endColor = randomHslColor();
            let startHue = parseInt(startColor.match(/hsl\((\d+)/)[1]);
            let endHue = parseInt(endColor.match(/hsl\((\d+)/)[1]);
            angle = Math.abs(startHue - endHue);
        } while (angle < 60); // Minimum hue difference threshold

        gradients[i].style.setProperty('--angle', possibleAngles[randomIndex] + 'deg'); // Random angle between the possible angles
        gradients[i].style.setProperty('--start-color', startColor);
        gradients[i].style.setProperty('--end-color', endColor);
    }

});