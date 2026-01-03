import { computed } from 'vue'
import { useLocalStorage } from './useLocalStorage'
import { useToast } from './useToast'

export function useFavorites() {
  // persistent ref stored in localStorage via the composable
  const favorites = useLocalStorage('favorites', [])

  // get toast helper (safe fallback if useToast isn't available)
  let showToast = (msg) => console.info(msg)
  try {
    const toast = useToast()
    showToast = toast.showToast ?? showToast
  } catch (e) {
    // If import fails at runtime, we still want favorites to work
    console.warn('useToast not available, continuing without UI toasts.', e)
  }

  const isFavorite = (productId) =>
    favorites.value.some(fav => fav.id === productId)

  const toggleFavorite = (product = {}) => {
    if (!product || (!product.id && product.id !== 0)) {
      console.warn('toggleFavorite called with invalid product:', product)
      return
    }

    if (isFavorite(product.id)) {
      favorites.value = favorites.value.filter(fav => fav.id !== product.id)
      showToast('Removed from favorites', 'success')
      return false // removed
    } else {
      // push a sanitized product object (avoid adding huge objects)
      favorites.value = [
        ...favorites.value,
        {
          id: product.id,
          name: product.name ?? '',
          price: product.price ?? null,
          image: product.image ?? null,
          category: product.category ?? null,
          addedAt: new Date().toISOString()
        }
      ]
      showToast('Added to favorites!', 'success')
      return true // added
    }
  }

  const favoritesCount = computed(() => favorites.value.length)

  return {
    favorites,
    isFavorite,
    toggleFavorite,
    favoritesCount
  }
}