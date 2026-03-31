import { useI18n } from 'vue-i18n'
import { useLanguageStore } from '../stores/language'

export function useRelativeTime() {
  const { locale } = useI18n()
  const languageStore = useLanguageStore()

  /**
   * Format a date to relative time (e.g., "2 hours ago", "৫ মিনিট আগে")
   * @param {string|Date} date - The date to format
   * @returns {string} - Formatted relative time string
   */
  function formatRelative(date) {
    const now = new Date()
    const past = new Date(date)
    const diffInSeconds = Math.floor((now - past) / 1000)
    const currentLocale = locale.value || languageStore.current

    // Bangla numbers for formatting
    const toBanglaNumber = (num) => {
      if (currentLocale !== 'bn') return num.toString()
      const banglaDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯']
      return num.toString().split('').map(d => banglaDigits[parseInt(d)] || d).join('')
    }

    // Time units in seconds
    const minute = 60
    const hour = minute * 60
    const day = hour * 24
    const week = day * 7
    const month = day * 30
    const year = day * 365

    // Format based on locale
    const formatUnit = (value, unitBn, unitEn) => {
      const num = toBanglaNumber(value)
      if (currentLocale === 'bn') {
        return `${num} ${unitBn} আগে`
      }
      return `${value} ${unitEn}${value > 1 ? 's' : ''} ago`
    }

    if (diffInSeconds < minute) {
      return currentLocale === 'bn' ? 'এইমাত্র' : 'Just now'
    } else if (diffInSeconds < hour) {
      const minutes = Math.floor(diffInSeconds / minute)
      return formatUnit(minutes, 'মিনিট', 'minute')
    } else if (diffInSeconds < day) {
      const hours = Math.floor(diffInSeconds / hour)
      return formatUnit(hours, 'ঘণ্টা', 'hour')
    } else if (diffInSeconds < week) {
      const days = Math.floor(diffInSeconds / day)
      return formatUnit(days, 'দিন', 'day')
    } else if (diffInSeconds < month) {
      const weeks = Math.floor(diffInSeconds / week)
      return formatUnit(weeks, 'সপ্তাহ', 'week')
    } else if (diffInSeconds < year) {
      const months = Math.floor(diffInSeconds / month)
      return formatUnit(months, 'মাস', 'month')
    } else {
      const years = Math.floor(diffInSeconds / year)
      return formatUnit(years, 'বছর', 'year')
    }
  }

  /**
   * Format a date to a readable format
   * @param {string|Date} date - The date to format
   * @returns {string} - Formatted date string
   */
  function formatDate(date) {
    const d = new Date(date)
    const currentLocale = locale.value || languageStore.current

    const options = {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    }

    return d.toLocaleDateString(currentLocale === 'bn' ? 'bn-BD' : 'en-US', options)
  }

  /**
   * Format a date to time only
   * @param {string|Date} date - The date to format
   * @returns {string} - Formatted time string
   */
  function formatTime(date) {
    const d = new Date(date)
    const currentLocale = locale.value || languageStore.current

    const options = {
      hour: 'numeric',
      minute: '2-digit',
      hour12: true
    }

    return d.toLocaleTimeString(currentLocale === 'bn' ? 'bn-BD' : 'en-US', options)
  }

  /**
   * Format a date to full date and time
   * @param {string|Date} date - The date to format
   * @returns {string} - Formatted date and time string
   */
  function formatDateTime(date) {
    const d = new Date(date)
    const currentLocale = locale.value || languageStore.current

    const options = {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: 'numeric',
      minute: '2-digit',
      hour12: true
    }

    return d.toLocaleString(currentLocale === 'bn' ? 'bn-BD' : 'en-US', options)
  }

  return {
    formatRelative,
    formatDate,
    formatTime,
    formatDateTime
  }
}
