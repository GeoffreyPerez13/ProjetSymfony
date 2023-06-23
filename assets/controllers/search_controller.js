import { Controller } from '@hotwired/stimulus';


export default class extends Controller {
    static targets = ["datalist"]
    static values = {
        url: String,
        urlplace: String,
        urlpicture: String
    }

    connect() {
        this.isWaiting = false
    }

    async searchUpdate() {
        
        let keyword = event.target.value
        if (keyword.length > 2 && ! this.isWaiting) {
            this.datalistTarget.style.display = 'block'
            this.datalistTarget.innerHTML = '<div class="spinner-grow text-info" role="status"><span class="visually-hidden">Chargement</span></div>'
            let url = `${this.urlValue}/${encodeURI(keyword)}`
            this.isWaiting = true
            const response = await fetch(url)
            const places = await response.json()
            this.isWaiting = false
            console.log(places)
            this.datalistTarget.innerHTML = ''
            if(places.length > 0) {
                places.forEach(place => {
                    const dataHtml = document.createElement('div')
                    dataHtml.classList.add('media')
                    dataHtml.innerHTML = `
                    <div class="row">
                        <div class="col-4">
                            <img class="img-fluid" src="${this.urlpictureValue}${place.pictures[0].file}" class="mr - 3" alt="${place.pictures[0].title}">
                        </div>
                        <div class="col-8">
                            <h5 class="mt-0">${place.name}</h5>
                            <a href="${this.urlplaceValue}/${place.slug}" class="btn btn-outline-info">Voir</a>
                        </div>
                    </div>
                    `
                    this.datalistTarget.appendChild(dataHtml)
                });
            }
            else
                this.datalistTarget.innerHTML = 'Pas de résultats trouvés !'
        }
        
    }
}
