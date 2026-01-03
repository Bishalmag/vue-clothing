import { ref, watch } from 'vue'

export function useLocalStorage(key, defaultValue) {
  const stored = localStorage.getItem(key)
  let parsed
  try {
    parsed = stored !== null ? JSON.parse(stored) : defaultValue
  } catch {
    parsed = stored !== null ? stored : defaultValue
  }

  const value = ref(parsed)

  watch(
    value,
    (newVal) => {
      if (newVal === null || newVal === undefined) {
        localStorage.removeItem(key)
      } else if (typeof newVal === 'object') {
        localStorage.setItem(key, JSON.stringify(newVal))
      } else {
        localStorage.setItem(key, String(newVal)) 
      }
    },
    { deep: true }
  )

  return value
}