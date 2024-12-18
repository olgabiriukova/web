function getSessionStorage(key) {
    return sessionStorage.getItem(key);
}

function setSessionStorage(key, value) {
    sessionStorage.setItem(key, value);
}


let currentPage;
if(getSessionStorage('currentPage')!=null){
    currentPage = parseInt(getSessionStorage('currentPage'), 10);
}else{
    currentPage = 1;
}

loadCategoriesWithPagination(currentPage);

document.addEventListener('DOMContentLoaded', function () {
    console.log(currentPage);
   
document.querySelector('.category-pagination').addEventListener('click', function (event) {
if (event.target.tagName === 'A') {
    event.preventDefault();
    const pageClicked = parseInt(event.target.dataset.page);
    loadCategoriesWithPagination(pageClicked);
    
}

});
    
});

function displayCategories(categories) {
const categoryList = document.getElementById('categoryList');
categoryList.innerHTML = '';

categories.forEach(category => {
    const listItem = document.createElement('li');
    listItem.textContent = category.name;
    listItem.classList.add('category-item');

    listItem.addEventListener('click', function () {
        currentCategoryId = category.id;
        window.location.href = 'jobs.php?category=' + currentCategoryId;
    });

    categoryList.appendChild(listItem);
});
}

function loadCategoriesWithPagination(page) {
const xhr = new XMLHttpRequest();
const url = 'getData.php?page=' + page;

xhr.onreadystatechange = function () {
if (xhr.readyState === XMLHttpRequest.DONE) {
    if (xhr.status === 200) {
        try {
            const response = JSON.parse(xhr.responseText);
            displayCategories(response.categories);
            displayCategoryPagination(response.totalCategoryPages, page);
            setSessionStorage('currentPage', page);
        } catch (error) {
            console.error('pasing error', error);
        }
    } else {
        console.error('error data', xhr.status, xhr.statusText);
    }
}
};

xhr.open('GET', url, true);
xhr.send();


}

function displayCategoryPagination(totalPages, currentPage) {
const categoryPaginationContainer = document.querySelector('.category-pagination');
categoryPaginationContainer.innerHTML = '';

if (totalPages > 0) {
let paginationHTML = '';

if (currentPage > 1) {
    paginationHTML += '<a href="#" class="prev" data-page="' + (currentPage - 1) + '">&laquo; Previous</a>';
}

for (let i = 1; i <= totalPages; i++) {
    paginationHTML += '<a href="#" class="page' + (currentPage === i ? ' active' : '') + '" data-page="' + i + '">' + i + '</a>';
}

if (currentPage < totalPages) {
    paginationHTML += '<a href="#" class="next" data-page="' + (currentPage + 1) + '">Next &raquo;</a>';
}

categoryPaginationContainer.innerHTML = paginationHTML;


const pageLinks = categoryPaginationContainer.querySelectorAll('a[data-page]');
pageLinks.forEach(link => {
    link.addEventListener('click', function(event) {
        event.preventDefault();
        const pageClicked = parseInt(link.dataset.page);
        currentPage = pageClicked;
        loadCategoriesWithPagination(pageClicked);

    });
});
}

}