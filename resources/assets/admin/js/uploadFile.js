var clone = {}

const { validation } = window.translations;

function changeFile(event) {
    var customFile = document.getElementById('customFile');
    var val = customFile.value;
    var last = val.substring(val.lastIndexOf('.') + 1).toLowerCase();
    switch(last){
        case 'jpg': case 'jpeg': case 'png': case '' :
            break;
        default:
            customFile.value = '';
            swal({
                type: 'error',
                icon: 'warning',
                text: validation.custom.invalidImage
            });
            break;
    };

    var fileElement = event.target;
    if (fileElement.value === '') {
        document.getElementById('custom').appendChild(clone[fileElement.id]);
        customFile.parentNode.removeChild(customFile);

    }
    var path = document.getElementById('customFile').value;
    document.getElementById('label').innerHTML = path;
}

function clickFile(event) {
    var fileElement = event.target;
    if (fileElement.value != '') {
        clone[fileElement.id] = fileElement.cloneNode(true);
    }
}

jQuery(document).ready(function() {
    var language = $('header').data('language');
    var textNotify = 'Image require less than 2MB';
    if (language == 'vi') {
        textNotify = 'Ảnh yêu cầu nhỏ hơn 2MB';
    }
    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('.img-book').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#customFile').change(function() {
        if (this.files[0].size > 2097152) {
            swal({
                type: 'error',
                title: 'Oops...',
                text: textNotify,
            });
            $('#label').text('Browese...');
            $('.img-book').attr('src', '');
            $(this).val('');
        } else {
            readURL(this);
        }
    });

    $('#cancel').click(function() {
        $('#label').text('');
        $('.img-book').attr('src', '');
    })
});
