import {ref} from 'vue'
import {Inertia} from '@inertiajs/inertia'

export default function weight(weight = null, weightbath = null) {

    return {
        weight: weight,
        weightbaht: weightbath,
        toGram() {
            return weightbath ? (weight * 15.2) : weight;
        }
    }
}

export function useForm(data) {
    return ref(form(data))
}
