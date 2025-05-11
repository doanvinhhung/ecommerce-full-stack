export const BASE_URL = 'https://information.22web.org/public/api'
export const headersConfig = (token,contentType) => {
  const config = {
    headers: {
      "Authorization": `Bearer ${token}`,
      "Content-type": contentType || "application/json"
    }
  }
  return config
}
export const makeUniqueId = (length) => {
    let result = ''
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
    const charactersLength = characters.length
    let counter = 0
    while (counter < length) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength))
      counter += 1
    }
    return result
  }