import {ref} from 'vue'
import {Inertia} from '@inertiajs/inertia'

export default function weight(weight = null, weightbaht = null) {

    return {
        weight: weight,
        weightbaht: weightbaht,
        toGram() {
            return weightbaht ? weight * 15.2 : weight;
        }
    }
}
