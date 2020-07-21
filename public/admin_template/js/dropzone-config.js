$(document).ready( function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });
    let uploadedDocumentMap = []; // save all upload file id
    let uploadedHistory = []; // save new upload file id
    let indexFile = 0;
    const maxFiles = 5;
    let count = 0;
    Dropzone.options.myDropzone = {
        url: $("div#my-dropzone").attr("route-upload"),
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        autoProcessQueue: true,
        uploadMultiple: true,
        parallelUploads: 5,
        maxFiles: maxFiles,
        maxFilesize: 5,
        acceptedFiles: ".jpeg,.jpg,.png,.gif",
        dictFileTooBig: "Dung lượng ảnh không được vượt quá 5MB",
        dictInvalidFileType: "Ảnh không đúng định dạng",
        dictMaxFilesExceeded: "Bạn chỉ có thể chọn tối đa 5 ảnh",
        addRemoveLinks: true,
        init: function() {
            const myDropzone = this;
            $.ajax({
                url: $("div#my-dropzone").attr("route-get-images"),
                type: "get",
                data: {},
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    const data = response.data;
                    $.each(data, function(key, value) {
                        var mockFile = {
                            name: value.name,
                            serverId: value.id,
                            deleteRoute: value.deleteRoute
                        };
                        uploadedDocumentMap.push(value.id);
                        $("#form").append(
                            '<input type="hidden" name="images[]" value="' +
                                value.id +
                                '">'
                        );
    
                        myDropzone.emit("addedfile", mockFile);
                        myDropzone.emit("thumbnail", mockFile, value.url);
                        myDropzone.emit("complete", mockFile);
                    });
                }
            });
        },
        success: function(file, response) {
            count = 0;
            if (response.status === "success") {
                const { data } = response;
                console.log(response);
                for (let img of data) {
                    if (!uploadedDocumentMap.includes(img.id)) {
                        uploadedDocumentMap.push(img.id);
                        uploadedHistory.push(img);
    
                        $("#form").append(
                            '<input type="hidden" name="images[]" value="' +
                                img.id +
                                '">'
                        );
                    }
                }
                file.serverId = uploadedHistory[indexFile].id;
                file.deleteRoute = uploadedHistory[indexFile].deleteRoute;
                indexFile++;
            }
        },
        removedfile: function(file) {
            const imageId = file.serverId;
            if (imageId) {
                $.ajax({
                    type: "POST",
                    url: file.deleteRoute,
                    data: "id=" + imageId,
                    dataType: "html",
                    success: function(response) {
                        console.log(response);
                        $('input[name="images[]"]').each(function() {
                            if ($(this).val() == imageId) {
                                $(this).remove();
                            }
                        });
                        count = 0;
                        const index = uploadedDocumentMap.indexOf(imageId);
                        uploadedDocumentMap.splice(index, 1);
                    }
                });
            }
            var _ref;
            if (file.previewElement) {
                if ((_ref = file.previewElement) != null) {
                    _ref.parentNode.removeChild(file.previewElement);
                }
            }
            return this._updateMaxFilesReachedClass();
        },
        accept: function(file, done) {
            count++;
    
            if (uploadedDocumentMap.length + count > maxFiles) {
                console.log(`Bạn chỉ có upload tối đa ${maxFiles} file`);
                new PNotify({
                    title: "Thêm ảnh thất bại",
                    text: `Bạn chỉ có upload tối đa ${maxFiles} ảnh`,
                    type: "error",
                    styling: "bootstrap3"
                });
                this.removeFile(file);
            } else {
                done();
            }
        },
        previewsContainer: null,
        hiddenInputContainer: "body"
    };
    
    $(".btnAddInput").click(function(e) {
        e.preventDefault();
        const inputName = $(this).attr("data-input-name");
        let input = $(`<div class="wrapper-input">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 col-sm-offset-3" style="margin-bottom: 10px;">
                <input id="middle-name" class="form-control col-md-7 col-xs-12" type="text" name="${inputName}">
            </div>
            <div class="col-md-1 com-sm-1 col-xs-12">
                <button class="btn btn-danger btnRemoveInput"><i class="fa fa-trash"></i></button>
            </div>
        </div>
        `);
        const divParent = $(this).parent();
        $(divParent.parent()).append(input);
        $(".btnRemoveInput").bind("click", function(e) {
            e.preventDefault();
            removeInput($(this));
        });
    });
    $(".btnRemoveInput").bind("click", function(e) {
        e.preventDefault();
        removeInput($(this));        
    })
    
    function removeInput(e) {
        const divParent = e.parent();
        $(divParent.parent()).remove();
        return false;
    }
    
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
    
            reader.onload = function(e) {
                $("#previewImage").attr("src", e.target.result);
                $("#previewImage").show();
            };
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        } else {
            $("#previewImage").attr("src", "#");
            $("#previewImage").hide();
        }
    }
    
    $("#image").change(function() {
        previewImage(this);
    });
    });
    