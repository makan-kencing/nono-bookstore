"use strict";

class Upload {
    FIELD_NAME = "file";

    /**
     * @param {File} file
     */
    constructor(file)
    {
        this.file = file;
    }

    getType()
    {
        return this.file.type;
    }

    getSize()
    {
        return this.file.size;
    }

    getName()
    {
        return this.file.name;
    }

    /**
     * @param {string} url The POST endpoint for uploading the file.
     * @param {function(ProgressEvent): any} onProgress
     * @param {function(object, string, XMLHttpRequest): void} onSuccess
     * @param {function(XMLHttpRequest, string, string): void} onError
     */
    upload(
        url,
        onProgress = null,
        onSuccess = null,
        onError = null
    ) {
        const formData = new FormData();

        formData.append(this.FIELD_NAME, this.file, this.getName());

        $.post(
            url,
            formData,
            onSuccess,
            "json",
            {
                xhr: function () {
                    // Get the native XHR object
                    let xhr = $.ajaxSettings.xhr();
                    if (xhr.upload) {
                        // Monitor upload progress
                        xhr.upload.onprogress = onProgress;
                    }
                    return xhr;
                },
                error: onError,
                async: true,
                cache: false,
                contentType: false, // Essential for FormData
                processData: false, // Essential for FormData
                timeout: 60000 // Optional timeout
            }
        )
    }
}
