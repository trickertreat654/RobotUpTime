<script setup>
import Stats from '@/Components/Stats.vue'
import Stats2 from '@/Components/Stats2.vue'
import { ref } from 'vue'


defineProps({
  stats: Object
})



const secondaryNavigation = [
    { name: 'Overview', href: '#', current: true },
    { name: 'Activity', href: '#', current: false },
    { name: 'Settings', href: '#', current: false },
    { name: 'Collaborators', href: '#', current: false },
    { name: 'Notifications', href: '#', current: false },
  ]
 

  
const currentTab = ref('Stats')

const tabs = {
 Stats
}


</script>
<template>
   <header>
            <!-- Secondary navigation -->
            <!-- <div class="h-12 bg-gray-700/10"></div> -->
            <nav class="flex py-4 overflow-x-auto border-b border-white/10">
              <div role="list" class="flex flex-none min-w-full px-4 text-sm font-semibold leading-6 text-gray-400 gap-x-6 sm:px-6 lg:px-8">
                <button
       v-for="(_, tab) in tabs"
       :key="tab"
       :class="['tab-button', { active: currentTab === tab }]"
       @click="currentTab = tab"
     >
      {{ tab }}
    </button>
    
  
</div>
</nav>
<component :is="tabs[currentTab]" :stats class="tab"></component>
  <!-- <component :is="Stats" /> -->
          </header>

</template>