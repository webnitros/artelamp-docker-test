class getFiles {
    constructor () {
        this.data = null
        this.template = {
            template: ``,
            render: function () {

            }
        }
        if (typeof filesData == 'undefined') {
            this.ajax()
        } else {
            this.data = filesData.object
            this.init()
        }

    }
    ajax () {
        var $artikul_1c = $('#ms2_form_article')
        if ($artikul_1c.length) {
            var article = $artikul_1c.val()
            var self = this
            $.ajax({
                method: 'GET',
                url: '/getDocsByArts?article=' + article,
                cache: false,
                dataType: 'JSON',
            }).done(function (data) {
                if(data.object) {
                    self.data = data.object
                }
            }).fail(function () {
                self.data = null
            }).always(function () {
                self.init()
            })
        }
    }


    init () {
        let counter = 0
        let url
        for(let dataKey in this.data) {
            if(this.data.hasOwnProperty(dataKey)) {

                $(`<div class="card_characters_list_content_title document_for_download">
                        ${dataKey}
                   </div>
                   <ul class="card_characters_list_content_block document_for_download" data-list="${counter}">

                    </ul>
                   `).appendTo('#fileList')

                const files = this.data[dataKey]
                if(files instanceof Array && files.length > 0) {
                    for(const fileId in files) {
                        let name = files[fileId].internal_name
                        if(files.length > 1) {
                            name += ` №${fileId}`
                        }
                        if(!files[fileId].download.startsWith('http')) {
                            url = 'https://fandeco.ru' + files[fileId].download
                        } else {
                            url = files[fileId].download ?? false
                        }
                        if(name && url) {
                            const row = `<li>
                                          <div class="name">
                                             <a href="${url}"  target="_blank">
                                                 ${name}
                                             </a>
                                          </div>
                                      </li>`
                            $(row).appendTo(`ul[data-list=${counter}]`)
                        }
                    }
                    counter++
                }
            }
        }
        if(counter === 0) {
            $('.document_for_download').fadeOut(0)
        } else {
            $('.document_for_download').fadeIn(0)
        }
    }
}

var files = new getFiles()