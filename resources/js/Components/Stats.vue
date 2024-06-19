<script setup>
import { ref, reactive, watch, onMounted, computed } from 'vue'
import gsap from 'gsap'

const props = defineProps({
  stats: Object,
  device: Object,
  status: Object,
})

const number = ref(0)
const tweened = reactive({
  number: 0
})

watch(number, (n) => {
  gsap.to(tweened, { duration: 1, number: Number(n) || 0 })
})
const number2 = ref(0)
const tweened2 = reactive({
  number2: 0
})

watch(number2, (n) => {
  gsap.to(tweened2, { duration: 1, number2: Number(n) || 0 })
})
const number3 = ref(0)
const tweened3 = reactive({
  number3: 0
})

watch(number3, (n) => {
  gsap.to(tweened3, { duration: 1, number3: Number(n) || 0 })
})
const number4 = ref(0)
const tweened4 = reactive({
  number4: 0
})

watch(number4, (n) => {
  gsap.to(tweened4, { duration: 1, number4: Number(n) || 0 })
})

onMounted(() => {
  number.value = props.stats[0] ? props.stats[0].value : 0
  number2.value = props.stats[1] ? props.stats[1].value : 0 
  number3.value = props.stats[2] ? props.stats[2].value : 0
  number4.value = props.stats[3] ? props.stats[3].value : 0
})

console.log(props)

const online = computed(() => {
  if(props.status) {
    return props.status.status === '1' 
  } else {
    return false
  }
})

const statIdx = ref(2)

</script>
<template>

  <!-- Heading -->
  <div class="flex flex-col items-start justify-between px-4 py-4 gap-x-8 gap-y-4 bg-gray-700/10 sm:flex-row sm:items-center sm:px-6 lg:px-8">
              <div>
                <div class="flex items-center gap-x-3">
                  <div class="flex-none p-1 rounded-full" :class="online? 'bg-green-400/10 text-green-400':'bg-red-400/10 text-red-400'">
                    <div class="w-2 h-2 bg-current rounded-full" />
                  </div>
                  <h1 class="flex text-base leading-7 gap-x-3">
                    <span class="font-semibold text-white">{{ props.device.name }}</span>
                    <span class="text-gray-600">/</span>
                    <span class="font-semibold text-white">{{online? 'Internet Connected':'Something\'s wrong'}}</span>
                  </h1>
                </div>
                <p class="mt-2 text-xs leading-6 text-gray-400">Served from Local Device via 192.168.1.177</p>
              </div>
              <div class="flex-none order-first px-2 py-1 text-xs font-medium rounded-full text-custom-blue-dim bg-custom-blue-dim/10 ring-1 ring-inset ring-custom-blue-dim/30 sm:order-none">Production</div>
            </div>
  
            <!-- Stats -->
            <div class="grid grid-cols-1 bg-gray-700/10 sm:grid-cols-2 lg:grid-cols-4">
              <div :class="[statIdx % 2 === 1 ? 'sm:border-l' : statIdx === 2 ? 'lg:border-l' : '', 'border-t border-white/5 py-6 px-4 sm:px-6 lg:px-8']">
                <p class="text-sm font-medium leading-6 text-gray-400">{{ props.stats[0].name}}</p>
                <p class="flex items-baseline mt-2 gap-x-2">
                  <span class="text-4xl font-semibold tracking-tight text-white">{{ tweened.number.toFixed(0)   }}</span>
                  <span v-if="false" class="text-sm text-gray-400">{{ tweened.number.toFixed(0)  }}</span>
                </p>
              </div>
              <div :class="[statIdx % 2 === 1 ? 'sm:border-l' : statIdx === 2 ? 'lg:border-l' : '', 'border-t border-white/5 py-6 px-4 sm:px-6 lg:px-8']">
                <p class="text-sm font-medium leading-6 text-gray-400">{{ props.stats[1].name}}</p>
                <p class="flex items-baseline mt-2 gap-x-2">
                  <span class="text-4xl font-semibold tracking-tight text-white">{{ tweened2.number2.toFixed(0)  }}</span>
                  <span v-if="true" class="text-sm text-gray-400">hours</span>
                </p>
              </div>
              <div :class="[statIdx % 2 === 1 ? 'sm:border-l' : statIdx === 2 ? 'lg:border-l' : '', 'border-t border-white/5 py-6 px-4 sm:px-6 lg:px-8']">
                <p class="text-sm font-medium leading-6 text-gray-400">{{ props.stats[2].name}}</p>
                <p class="flex items-baseline mt-2 gap-x-2">
                  <span class="text-4xl font-semibold tracking-tight text-white">{{ tweened3.number3.toFixed(0)  }}</span>
                  <span v-if="false" class="text-sm text-gray-400">yo mama</span>
                </p>
              </div>
              <div :class="[statIdx % 2 === 1 ? 'sm:border-l' : statIdx === 2 ? 'lg:border-l' : '', 'border-t border-white/5 py-6 px-4 sm:px-6 lg:px-8']">
                <p class="text-sm font-medium leading-6 text-gray-400">{{ props.stats[3].name}}</p>
                <p class="flex items-baseline mt-2 gap-x-2">
                  <span class="text-4xl font-semibold tracking-tight text-white">{{ tweened4.number4.toFixed(0)  }}</span>
                  <span v-if="false" class="text-sm text-gray-400">yo mama</span>
                </p>
              </div>
            </div>

</template>