let index = 0;

const carouselItems = document.getElementsByClassName("carousel-item");
const dotsContainer = document.getElementById("dots");
let dots = [];
for (let i = 0; i < carouselItems.length; i++) {
    dots.push(dotsContainer.appendChild(document.createElement("div")));
    dots[i].classList.add("dot");
}

const displayCarouselItems = n => {
    //console.log(n);
    // Carousel loop
    if (n > carouselItems.length - 1) {index = 0}
    if (n < 0) {index = carouselItems.length - 1}
    
    // Hide all carousel items
    for (let i = 0; i < carouselItems.length; i++) {
        carouselItems[i].style.display = "none";
    }
    // Display current carousel item
    carouselItems[index].style.display = "grid";

    // Display dots for each carousel item
    
    // Highlight current dot
    dots.forEach(dot => dot.classList.remove("active"));
    dots[index].classList.add("active");
}

displayCarouselItems(index);
const nextCarouselItem = () => {
    index++;
    displayCarouselItems(index);
};

const prevCarouselItem = () => {
    index--;
    displayCarouselItems(index);
};

// add event listeners to next and previous buttons
document.getElementById("carousel__btn-next").addEventListener("click", () => nextCarouselItem());
document.getElementById("carousel__btn-prev").addEventListener("click", () => prevCarouselItem());