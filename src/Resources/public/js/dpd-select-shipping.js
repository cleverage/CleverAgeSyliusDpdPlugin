const manageDpdMap = (element, removePickup = null) => {
  const dpdMapContainer = document.getElementsByClassName('dpd-map-container')[0]
  const isdpdPickup = typeof element.dataset.dpdPickup !== 'undefined'

  if (removePickup) removePickup.click()

  if (isdpdPickup) {
    if (dpdMapContainer.classList.contains('hasPickupPoint')) {
      dpdMapContainer.classList.remove('hasPickupPoint')
    } else {
      dpdMapContainer.classList.add('active')
    }
  } else {
    dpdMapContainer.classList.remove('active')
  }

  const nextStep = document.getElementById('next-step')
  if (nextStep) {
    const selectedPickup = document.querySelector('.dpd-selected-pickup-container')

    if (isdpdPickup && selectedPickup.hasAttribute('hidden')) {
      nextStep.setAttribute('disabled', 'disabled')
    } else {
      nextStep.removeAttribute('disabled')
    }
  }
}

const choices = document.querySelectorAll('form[name="sylius_checkout_select_shipping"] input[type="radio"]')

if (choices.length) {
  const defaultChecked = document.querySelector('form[name="sylius_checkout_select_shipping"] input[type="radio"][checked]')
  const removePickup = document.getElementById('dpd-remove-pickup')

  if (defaultChecked) {
    manageDpdMap(defaultChecked, removePickup)
  }

  choices.forEach(elem => {
    elem.addEventListener('change', function () {
      if (elem.classList.contains('input-shipping-method')) {
        removePickup.click()
        manageDpdMap(this)
      }
    })
  })
}
