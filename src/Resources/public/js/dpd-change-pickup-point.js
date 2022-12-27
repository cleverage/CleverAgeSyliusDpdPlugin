const removeDpdPickup = document.getElementById('dpd-remove-pickup')

if (removeDpdPickup) {
  removeDpdPickup.addEventListener('click', function () {
    // Show Map
    document.getElementsByClassName('dpd-map-container')[0].classList.add('active')
    // Remove selected pickup text
    document.getElementById('dpd-selected-pickup').innerText = ''
    // Hide selected pickup
    document.querySelector('.dpd-selected-pickup-container').setAttribute('hidden', 'hidden')

    removeDpdPickupPointFromDatabase(removeDpdPickup)

    const nextStep = document.getElementById('next-step')
    if (nextStep) {
      nextStep.setAttribute('disabled', 'disabled')
    }
  })
}

const removeDpdPickupPointFromDatabase = element => {
  const {removeUrl, identifier} = element.dataset

  if (!identifier) {
    return
  }

  fetch(removeUrl + '?pickupPointId=' + identifier, {
    method: 'DELETE',
  })
    .then(response => response.json())
    .then(data => {
      // Do like you want !
        })
        .catch(error => console.log(error))
}
