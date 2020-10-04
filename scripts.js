window.onload = () => {
    duplicateImageContainers();
}

let timeout = null;

window.onresize = () => {
    if (timeout !== null) clearTimeout(timeout);

    timeout = setTimeout(duplicateImageContainers, 300);
}

function duplicateImageContainers() {
    const scrollers = document.querySelectorAll('.image-scroller');

    if (scrollers) {
        for (const scroller of scrollers) {
            const containers = scroller.querySelectorAll('.image-container');

            if (containers) {
                const container = containers[0];
                const width = Number.parseInt(scroller.dataset.width);
                const count = Math.ceil((window.innerWidth + width) / width);

                let length = containers.length;

                while (length < count) {
                    // Add a image container duplicate.
                    container.after(container.cloneNode(true));

                    length++;
                }
            }
        }
    }
}