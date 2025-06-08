import api from '@/axios'

export default {
  updateUser(userId, data) {
    return api.patch(`/users/${userId}`, data)
  }
}
