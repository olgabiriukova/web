
document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const jobId = urlParams.get('job');

    if (jobId !== null) {
        loadJobDetails(jobId);
    } else {
        console.error('jobId not found.');
    }

    const backButton = document.createElement('button');
    backButton.textContent = 'Back';
    backButton.classList.add('back-button-jobs');
    backButton.addEventListener('click', function () {
        window.history.back();
    });

   
    document.body.appendChild(backButton);
});

function loadJobDetails(jobId) {
    fetch('apply.php?job=' + jobId)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            displayJobDetails(data.job);
        })
        .catch(error => console.error('job error', error));
}

function displayJobDetails(job) {
    const jobDetailsContainer = document.getElementById('jobDetails');
    
    const jobTitle = document.createElement('h1');
    jobTitle.textContent = `${job.name} - ${job.salary !== undefined ? job.salary : 'N/A'}`;

    const jobDescription = document.createElement('a');

    jobDescription.textContent = job.description;


    jobDetailsContainer.appendChild(jobTitle);
    jobDetailsContainer.appendChild(jobDescription);
}

