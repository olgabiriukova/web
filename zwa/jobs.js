function setSessionStorage(key, value) {
    try {
        sessionStorage.setItem(key, value);
    } catch (e) {
        console.error('Session Storage Error:', e);
    }
}

function getSessionStorage(key) {
    try {
        return sessionStorage.getItem(key);
    } catch (e) {
        console.error('Session Storage Error:', e);
        return null;
    }
}

function getQueryParam(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}

const categoryId = getQueryParam('category');
let currentPage;
if(getSessionStorage('jobPage')!=null){
    currentPage = parseInt(getSessionStorage('jobPage'), 10);
}else{
    currentPage = 1;
}

document.addEventListener('DOMContentLoaded', function () {
        loadJobsWithPagination(categoryId, currentPage);

    document.querySelector('.job-pagination').addEventListener('click', function (event) {

        if (event.target.tagName === 'A') {
            event.preventDefault();
            const pageClicked = parseInt(event.target.dataset.page);
            setSessionStorage('jobPage', pageClicked);
            loadJobsWithPagination(categoryId, pageClicked);
        }
    });
    const backButton = document.createElement('button');
    backButton.textContent = 'Back to categories';
    backButton.classList.add('back-button');
    backButton.addEventListener('click', function () {
        setSessionStorage('jobPage', 1);
        window.history.back();
    });

   
    document.body.appendChild(backButton);
});

function loadJobsWithPagination(categoryId, page) {
    const xhr = new XMLHttpRequest();
    const storedCategoryId = categoryId;
    const url = `getJobs.php?category=${storedCategoryId}&page=${page}`;

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    displayJobs(response.jobs);
                    displayJobPagination(response.totalJobPages, page);

                } catch (error) {
                    console.error('error', error);
                }
            } else {
                console.error('error', xhr.status, xhr.statusText);
            }
        }
    };

    xhr.open('GET', url, true);
    xhr.send();
}

function displayJobPagination(totalPages, currentPage) {
    const jobPaginationContainer = document.querySelector('.job-pagination');
    jobPaginationContainer.innerHTML = '';

    if (totalPages > 0) {
        let paginationHTML = '';

        if (currentPage > 1) {
            paginationHTML += `<a href="#" class="prev" data-page="${currentPage - 1}">&laquo; Previous</a>`;
        }

        for (let i = 1; i <= totalPages; i++) {
            paginationHTML += `<a href="#" class="page${currentPage === i ? ' active' : ''}" data-page="${i}">${i}</a>`;
        }

        if (currentPage < totalPages) {
            paginationHTML += `<a href="#" class="next" data-page="${currentPage + 1}">Next &raquo;</a>`;
        }

        jobPaginationContainer.innerHTML = paginationHTML;

        const pageLinks = jobPaginationContainer.querySelectorAll('a[data-page]');
        pageLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const pageClicked = parseInt(link.dataset.page);
                loadJobsWithPagination(categoryId, pageClicked);
            });
        });
    }
}

function displayJobs(jobs) {
    const jobsList = document.getElementById('jobsList');

    jobsList.innerHTML = '';

    jobs.forEach(job => {
        const listItem = document.createElement('li');
        listItem.textContent = job.name;
        listItem.classList.add('jobs-item');
        listItem.addEventListener('click', function () {
            window.location.href = `jobsDescription.php?job=${job.id}`;
        });

        jobsList.appendChild(listItem);
    });
}