import { computed } from 'vue'
import { useLocalStorage } from './useLocalStorage'
import { useToast } from './useToast'

export function useFavorites() {
  const favorites = useLocalStorage('favorites', [])
  let showToast = msg => console.info(msg)
  try { showToast = useToast().showToast ?? showToast } catch {}

  const isFavorite = id => favorites.value.some(f => f.id === id)

  const toggleFavorite = product => {
    if (!product || !('id' in product)) return false
    if (isFavorite(product.id)) {
      favorites.value = favorites.value.filter(f => f.id !== product.id)
      showToast('Removed from favorites', 'success')
      return false
    } else {
      favorites.value.push({
        id: product.id,
        name: product.name ?? '',
        price: product.price ?? null,
        image: product.image ?? null,
        category: product.category ?? null,
        addedAt: new Date().toISOString()
      })
      showToast('Added to favorites!', 'success')
      return true
    }
  }

  const favoritesCount = computed(() => favorites.value.length)

  return { favorites, isFavorite, toggleFavorite, favoritesCount }
}
