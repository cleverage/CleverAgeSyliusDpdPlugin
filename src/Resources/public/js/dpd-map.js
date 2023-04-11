import * as L from "https://cdn.jsdelivr.net/npm/leaflet@1.8.0/dist/leaflet-src.esm.js"

const DEFAULT_POSITION = [48.866667, 2.333333]

export class DpdMap {
  constructor() {
    this.mapSelector = document.getElementById('dpd-map')
    this.pickupPoints = JSON.parse(this.mapSelector.dataset.points)
    this.firstCoords = []
    this.map = L.map('dpd-map', {
      center: DEFAULT_POSITION,
      zoom: 13,
    })

    // Initialize map
    this.init()
    // Center map to pickupPoints
    this.panToFirstCoords()
  }

  panToFirstCoords() {
    if (this.pickupPoints?.length > 0) {
      this.firstCoords = this.pickupPoints[0]
      this.map.panTo([
        this.firstCoords.latitude.replace(',', '.'),
        this.firstCoords.longitude.replace(',', '.'),
      ])
    }
  }

  init() {
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(this.map)

    if (this.pickupPoints?.length > 0) {
      this.setMarkers()
    }
  }

  setMarkers() {
    for (const pickupPoint of this.pickupPoints) {
      let customIcon = L.divIcon({className: 'marker-icon'});

      let marker = L.marker([
        pickupPoint.latitude.replace(',', '.'),
        pickupPoint.longitude.replace(',', '.'),
      ], {icon: customIcon}).addTo(this.map)

      marker.bindPopup(this.getPopupContent(pickupPoint))
    }
  }

  getPopupContent(pickupPoint) {
    return `<div class="popup-pickup-content">
                   <div class="popup-pickup-type"></div>
                   <h2 class="popup-pickup-name">${pickupPoint.name}</h2>
                   <div class="popup-address">
                       <div class="upc">${pickupPoint.name.toLowerCase()}</div>
                       <div class="city">${pickupPoint.zipCode} ${pickupPoint.city.toLowerCase()}</div>
                   </div>
                   <a onclick="selectDpdPickupPoint(this)"
                      id="dpd-choose-pickup-point"
                      class="btn btn-primary bg-primary"
                      data-identifier="${pickupPoint.pudoId}"
                    >
                       <span>Choisir ce point relais</span>
                   </a>
                   <div class="popup-opening-hours">
                       <div>Horaires d'ouverture : </div>
                       <ul>
                            ${this.getOpeningHours(pickupPoint.openingHours)}
                       </ul>
                   </div>
               </div>`
  }

  getOpeningHours(formattedOpeningHours) {
    const days = [
      {name: 'Lun', openings: formattedOpeningHours.monday ?? []},
      {name: 'Mar', openings: formattedOpeningHours.tuesday ?? []},
      {name: 'Mer', openings: formattedOpeningHours.wednesday ?? []},
      {name: 'Jeu', openings: formattedOpeningHours.thursday ?? []},
      {name: 'Ven', openings: formattedOpeningHours.friday ?? []},
      {name: 'Sam', openings: formattedOpeningHours.saturday ?? []},
      {name: 'Dim', openings: formattedOpeningHours.sunday ?? []},
    ]

    const closings = [],
      openingHours = []

    days.forEach(day => {
      if (day.openings[0] === undefined) {
        closings.push(day.name)
      } else {
        openingHours.push(day)
      }
    })

    const openings = []
    openingHours.forEach(opening => {
      if (opening.openings.length === 1) {
        // One opening hour a day
        const existingOpening = openings.findIndex(o => o.openingHours === `${opening.openings[0].startTm} - ${opening.openings[0].endTm}`)
        if (existingOpening === -1) {
          // Not in array yet
          openings.push({
            days: opening.name,
            openingHours: `${opening.openings[0].startTm} - ${opening.openings[0].endTm}`,
          })
        } else {
          openings[existingOpening].days += `, ${opening.name}`
        }
      } else {
        // Two opening hours a day
        // Two opening hours a day
        const existingOpening = openings.findIndex(o => o.openingHours === `${opening.openings[0].startTm}-${opening.openings[0].endTm} - ${opening.openings[1].startTm}-${opening.openings[1].endTm}`)
        if (existingOpening === -1) {
          openings.push({
            days: opening.name,
            openingHours: `${opening.openings[0].startTm}-${opening.openings[0].endTm} - ${opening.openings[1].startTm}-${opening.openings[1].endTm}`,
          })
        } else {
          openings[existingOpening].days += `, ${opening.name}`
        }
      }
    })

    let list = ''
    openings.forEach(opening => {
      list += `
                <li>
                    <strong>${opening.days} :</strong> ${opening.openingHours}
                </li>
            `
    })

    if (closings.length) {
      list += `
                <li>
                    <strong>Jour${closings.length > 1 ? 's' : ''} fermé${closings.length > 1 ? 's' : ''}</strong> : ${closings.join(', ')}
                </li>
            `
    }

    return list
  }
}

// Init map
if (document.getElementById('dpd-map')) {
  new DpdMap().init()
}
