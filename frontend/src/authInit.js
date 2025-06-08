import { getAuth, onIdTokenChanged } from 'firebase/auth'
import { useAuthStore } from '@/stores/auth'
import { getUserWithToken } from '@/services/authService'

export function setupAuthListener() {
  const auth = getAuth()

  onIdTokenChanged(auth, async (firebaseUser) => {
    const authStore = useAuthStore()

    if (firebaseUser) {
      const token = await firebaseUser.getIdToken()
      authStore.token = token
      try {
        authStore.user = await getUserWithToken()
      } catch (e) {
        console.error('Error cargando el usuario desde el token:', e)
      }
    } else {
      authStore.logout()
    }
  })
}
