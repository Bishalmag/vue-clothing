import { computed } from 'vue'
import { useLocalStorage } from './useLocalStorage'
import { useToast } from './useToast'

export function useFavorites() {
  const favorites = useLocalStorage('favorites', [])
  const { showToast } = useToast() // Use showToast instead of success

  const isFavorite = (productId) => 
    favorites.value.some(fav => fav.id === productId)
  
  const toggleFavorite = (product) => {
    if (isFavorite(product.id)) {
      favorites.value = favorites.value.filter(fav => fav.id !== product.id)
      showToast('Removed from favorites', 'success') // Use showToast with type
    } else {
      favorites.value.push({
        id: product.id,
        name: product.name,
        price: product.price,
        image: product.image,
        category: product.category,
        addedAt: new Date().toISOString()
      })
      showToast('Added to favorites!', 'success') // Use showToast with type
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