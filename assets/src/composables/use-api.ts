import {createFetch} from '@vueuse/core'
import {useToastError} from './use-toast'
export const baseUrl = '/sterc-csp/'

function getInstance(options = {}) {
  return createFetch({
    baseUrl,
    options: {
      onFetchError({response, error, data}) {
        if (data) {
          useToastError(data)
        }
        return {response, error, data}
      },
      ...options,
    },
  })
}

export function useGet(endpoint: string, params = {}, options = {}) {
  if (Object.keys(params).length) {
    endpoint += '?' + new URLSearchParams(params).toString()
  }
  return getInstance(options)(endpoint).get().json()
}

export function usePost(endpoint: string, params = {}, options = {}) {
  return getInstance(options)(endpoint).post(params).json()
}

export function usePut(endpoint: string, params = {}, options = {}) {
  return getInstance(options)(endpoint).put(params).json()
}

export function usePatch(endpoint: string, params = {}, options = {}) {
  return getInstance(options)(endpoint).patch(params).json()
}

export function useDelete(endpoint: string, params = {}, options = {}) {
  return getInstance(options)(endpoint).delete(params).json()
}
