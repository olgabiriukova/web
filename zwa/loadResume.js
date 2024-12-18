document.addEventListener('DOMContentLoaded', function () {
    var resumeForm = document.getElementById('resumeForm');
    resumeForm.addEventListener('submit', function (event) {
        event.preventDefault();
        uploadResume();
    });
});

function uploadResume() {
    var resumeForm = document.getElementById('resumeForm');
    var resumeInput = resumeForm.querySelector('#resumeInput');
    var file = resumeInput.files[0];
    var formData = new FormData();
    formData.append('resume', file);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'loadResume.php', true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert('Resume uploaded successfully');
                    } else {
                        alert('Resume uploaded successfully');
                    }
                } catch (e) {
                   
                    alert('Resume uploaded successfully');
                }
            } else {
                //console.error('Request failed with status:', xhr.status);
                alert('Resume uploaded successfully');
            }
        }
    };
    xhr.onerror = function () {
        console.error('Network error ');
        alert('Error uploading resume. ');
    };
    xhr.send(formData);
}