import {ref} from 'vue'
import {Inertia} from '@inertiajs/inertia'

export default function weight(weight = 0, weightbaht = null) {

    return {
        weight: weight ?? 0,
        weightbaht: weightbaht,
        toGram() {
            return weightbaht ? weight * 15.2 : weight;
        }
    }
}
