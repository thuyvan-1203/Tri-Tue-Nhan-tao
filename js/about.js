jQuery(document).ready(function($) {
    $(".question-itemn").css({"color":"blue", "font-style":"italic"});
    $(".question-item").hide();
    $(".question-section").click(function(){
        $(this).next(".question-item").slideToggle(1000);
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const sliderContainer = document.querySelector('.slider-container');
    
    if (!sliderContainer) {
        return;
    }
    
    const prevButton = document.querySelector('.prev-button');
    const nextButton = document.querySelector('.next-button');
    const originalItems = document.querySelectorAll('.item:not(.clone)');

    if (!originalItems.length) {
        console.log('No slider items found');
        return;
    }

    let currentIndex = 1;
    let itemWidth = originalItems[0].offsetWidth;
    let isTransitioning = false;
    const numberOfOriginalItems = originalItems.length;
    const cloneCount = 1;
  
    const firstClones = Array.from(originalItems).slice(0, cloneCount).map(item => {
        const clone = item.cloneNode(true);
        clone.classList.add('clone');
        return clone;
    });
  
    const lastClones = Array.from(originalItems).slice(-cloneCount).map(item => {
        const clone = item.cloneNode(true);
        clone.classList.add('clone');
        return clone;
    });
  
    lastClones.forEach(clone => sliderContainer.insertBefore(clone, originalItems[0]));
    firstClones.forEach(clone => sliderContainer.appendChild(clone));
  
    let items = document.querySelectorAll('.item');
  
    sliderContainer.style.transform = `translateX(-${(currentIndex + cloneCount) * itemWidth}px)`;
  
    function updateSlider(animate = true) {
        const translateX = -(currentIndex + cloneCount) * itemWidth;
        sliderContainer.style.transition = animate ? 'transform 0.5s ease-in-out' : 'none';
        sliderContainer.style.transform = `translateX(${translateX}px)`;
    }
  
    function nextSlide() {
        if (isTransitioning) return;
        isTransitioning = true;
        currentIndex++;
        updateSlider();
    }
  
    function prevSlide() {
        if (isTransitioning) return;
        isTransitioning = true;
        currentIndex--;
        updateSlider();
    }
  
    if (nextButton) {
        nextButton.addEventListener('click', nextSlide);
    }
  
    if (prevButton) {
        prevButton.addEventListener('click', prevSlide);
    }
  
    sliderContainer.addEventListener('transitionend', () => {
        isTransitioning = false;
  
        if (currentIndex >= numberOfOriginalItems) {
            sliderContainer.style.transition = 'none';
            currentIndex = 0;
            updateSlider(false);
        }
  
        if (currentIndex < 0) {
            sliderContainer.style.transition = 'none';
            currentIndex = numberOfOriginalItems - 1;
            updateSlider(false);
        }
    });
  
    function updateItemWidth() {
        if (originalItems[0]) {
            itemWidth = originalItems[0].offsetWidth;
            updateSlider(false);
        }
    }

    window.addEventListener('resize', updateItemWidth);
  
    setTimeout(updateItemWidth, 100);
});