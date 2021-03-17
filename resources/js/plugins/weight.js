import {ref} from 'vue'
import {Inertia} from '@inertiajs/inertia'

export default function weight(weight = null, weightbath = null) {

    return {
        weight: weight,
        weightbaht: weightbath,
        toGram() {
            return weightbath ? 15.2 / (1/weight) : weight;
        }
    }
}

export function useForm(data) {
    return ref(form(data))
}
