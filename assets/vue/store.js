import {defineStore} from "pinia";

export const useReservationDataStore = defineStore('reservationData', {
    state: () => {
        return {
            stylistId: Number,
            serviceId: Number,
            selectedHour: Array,
            selectedDay: String
        }
    },
})
