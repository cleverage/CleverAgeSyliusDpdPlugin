window.selectDpdPickupPoint = function (element) {
  const pickupPointId = element.dataset.identifier
  if (!pickupPointId) {
    console.error('Pickup point id not found')
    return
  }

  const url = document.getElementById('dpd-map').dataset.setPickupUrl
  if (!url) {
    console.error('Set pickup url not found')
    return
  }

  const body = new FormData()
  body.append('pickupPointId', pickupPointId)

  fetch(url, {
    method: 'POST',
    body,
  })
    .then(response => response.json())
    .then(data => {
      document.dispatchEvent(new CustomEvent('dpdPickupPointSelected', {
        detail: {pickupPoint: data},
      }))
    })
    .catch(error => console.log(error))
}

document.addEventListener('dpdPickupPointSelected', function (e) {
  let {pickupPoint} = e.detail
  if (!pickupPoint) {
    return
  }

  pickupPoint = JSON.parse(pickupPoint)

  const dpdMap = document.getElementsByClassName('dpd-map-container')[0]
  const selectedPickup = document.getElementById('dpd-selected-pickup')
  const pickupContainer = document.querySelector('.dpd-selected-pickup-container')

  // Remove map && show selected pickup infos.
  dpdMap.classList.remove('active')

  selectedPickup.innerText = pickupPoint.name + ' ' + pickupPoint.address + ', ' + pickupPoint.zipCode + ', ' + pickupPoint.city
  pickupContainer.removeAttribute('hidden')

  // Set the identifier to the remove pickup button.
  const removePickup = document.getElementById('dpd-remove-pickup')
  removePickup.setAttribute('data-identifier', pickupPoint.pudoId)

  const nextStep = document.getElementById('next-step')
  if (nextStep) {
    // Go to next step available !
    nextStep.removeAttribute('disabled')
  }
})
