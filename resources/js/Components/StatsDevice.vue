<script setup>
import { ref, reactive, watch, onMounted, computed } from 'vue'
import gsap from 'gsap'

const props = defineProps({
  status: String,
  device: String,
  location: String,
  aTitle: String,
  aValue: Number,
  bTitle: String,
  bValue: Number,
  cTitle: String,
  cValue: Number,
  dTitle: String,
  dValue: Number,
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
  number.value = props.aValue ? props.aValue : 0
  number2.value = props.bValue ? props.bValue : 0
  number3.value = props.aValue ? props.aValue : 0
  number4.value = props.aValue ? props.aValue : 0
})

const online = computed(() => {
  if (props.status) {
    return props.status === '1'
  } else {
    return false
  }
})
const statIdx = ref(2)
</script>
<template>
  
 
          <span class="font-semibold text-black">{{ props.device }}</span>
          <span class="font-semibold text-black">{{ online ? 'Internet Connected' : 'Something\'s wrong' }}</span>
       
      <p class="mt-2 text-xs leading-6 text-gray-400">Served from {{ props.location }}</p>
   
   
  <!-- Stats -->

  <div class="flex flex-row justify-between">
    <div class="">
      <p class="text-sm font-medium leading-6 text-gray-400">{{ props.aTitle }}</p>
      <span class="text-4xl font-semibold tracking-tight text-black">{{ tweened.number.toFixed(0) }}</span>
    </div>
  
    <div class="">
      <p class="text-sm font-medium leading-6 text-gray-400">{{ props.bTitle }}</p>
      <span class="text-4xl font-semibold tracking-tight text-black">{{ tweened2.number2.toFixed(0) }}</span>
    </div>
<div class="">
  <p class="text-sm font-medium leading-6 text-gray-400">{{ props.cTitle }}</p>
  <span class="text-4xl font-semibold tracking-tight text-black">{{ tweened3.number3.toFixed(0) }}</span>
</div>
<div class="">
  <p class="text-sm font-medium leading-6 text-gray-400">{{ props.dTitle }}</p>
  <span class="text-4xl font-semibold tracking-tight text-black">{{ tweened4.number4.toFixed(0) }}</span>
</div>

  </div>
    
</template>