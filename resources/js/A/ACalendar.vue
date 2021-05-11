<template>
    <div class="grid grid-cols-7">
        <div v-for="cal in calendar"
             :key="cal"
             :class="[
                 'p-2 text-center my-1 w-full',
                 {
                     'text-gray-400':blurDate(cal),
                     'pointer-events-none': disabledClickDate(cal),
                     'cursor-pointer': disabledClickDate(cal) === false}]"
             @click="eventDate(cal)">
            <div :class="['p-2',{'bg-blue-500 text-white': resultDate === cal}] ">
                {{ filterDate(cal) }}
            </div>
        </div>
    </div>
    <div :class="['flex justify-items-center my-3 text-blue-700',{ 'hidden': resultDate === ''}]">{{ resultDate }}</div>
</template>

<script>
import {defineComponent, ref} from 'vue'
import {$date as useDate} from 'alga-js'

export default {
    name: "ACalendar",
    setup() {
        const year = ref(0)
        const month = ref(0)
        const resultDate = ref('')

        const dateNow = new Date()
        year.value = dateNow.getFullYear()
        month.value = Number(dateNow.getMonth()) + 1

        const calendar = useDate.calendar(year.value, month.value)
        const getDayNames = calendar.slice(0, 7)
        const filterDate = (date) => {
            let newDate = ''
            if (getDayNames.includes(date)) {
                newDate = date.slice(0, 3)
            } else {
                const splitDate = date.split('-')
                newDate = splitDate[2]
            }
            return newDate
        }

        const blurDate = (date) => {
            let blurText = false
            if (!getDayNames.includes(date) && Number(date.split('-')[1]) !== month.value) {
                blurText = true
            }
            return blurText
        }

        const disabledClickDate = (date) => {
            let disabledClickText = false
            if (getDayNames.includes(date) || Number(date.split('-')[1]) !== month.value) {
                disabledClickText = true
            }
            return disabledClickText
        }

        const eventDate = (date) => {
            resultDate.value = date
        }

        return {
            calendar, filterDate,
            blurDate, disabledClickDate, eventDate, resultDate
        }
    }
}
</script>

<style scoped>

</style>
