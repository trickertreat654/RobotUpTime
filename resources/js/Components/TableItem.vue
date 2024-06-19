<script setup>
import { useDateFormat, useNow } from '@vueuse/core'
import { Link, router } from '@inertiajs/vue3'
import { computed } from 'vue';

const props = defineProps({
  name: String,
  typeA: String,
  typeB: String,
  number: String,
  status: Object,
  datetime: String,
  updated_at: String,
  id: Number,
});

const theRoute = computed(() => {
  if (route().current('devices.index')) {
    return route('devices.show', props.id);
  }
  else if (route().current('groups.index')) {
    return route('groups.show', props.id);
  }
  else if (route().current('groups.show')) {
    return route('devices.show', props.id);
  }
});

const status = computed(() => {
  if (!props.status) {
    return 'text-rose-400 bg-rose-400/10';
  }
  return props.status === '1' ? 'text-green-400 bg-green-400/10' : 'text-rose-400 bg-rose-400/10';
});
</script>
<template>
  <Link :href="theRoute" as="tr"  class="flex flex-row justify-between hover:bg-custom-blue-dim/10" >
      <div class="text-black truncate ">{{ name }}</div>
      <div class="font-mono text-sm leading-6 text-gray-400">{{ typeA }}</div>
      <span
        class="text-black">{{
    typeB }}</span>
      <div class="flex-none p-1 rounded-full" :class="status">
      </div>
      <div class="hidden text-black sm:block">{{ status ? 'Online' : 'Offline' }}</div>
  <button class="w-full">
    {{ props.number }}
  </button>
    <time :datetime="updated_at">{{ useDateFormat(updated_at, 'M-D-YY HH:mm:ss') }}</time>
</Link>
</template>