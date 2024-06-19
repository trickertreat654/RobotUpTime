<script setup>
import TableItem from '@/Components/TableItem.vue'
import Stats from '@/Components/StatsDevice.vue'

const props = defineProps({
  items: Object,

  tableName: String,
  tableTypeA: String,
  tableTypeB: String,
  tableStatus: String,
  tableNumber: String,
  tableTime: String,

  statsStatus: String,
  statsDevice: String,
  statsLocation: String,
  statsATitle: String,
  statsAValue: Number,
  statsBTitle: String,
  statsBValue: Number,
  statsCTitle: String,
  statsCValue: Number,
  statsDTitle: String,
  statsDValue: Number,
})

console.log(props.items)
</script>

<template>
  <main>
    <Stats :status="props.statsStatus" 
    :device="props.statsDevice" 
    :location="props.statsLocation"
      :aTitle="props.statsATitle" 
      :aValue="props.statsAValue" 
      :bTitle="props.statsBTitle" 
      :bValue="props.statsBValue"
      :cTitle="props.statsCTitle" 
      :cValue="props.statsCValue" 
      :dTitle="props.statsDTitle" 
      :dValue="props.statsDValue">
    </Stats>
    <!-- Activity list -->
     <p></p>
              {{ props.tableName }}
              {{ props.tableTypeA }}/{{ props.tableTypeB }}
              {{ props.tableStatus }}
              {{ tableNumber }}
              {{ tableTime }}
          <TableItem v-for="item in props.items" 
          :name="item.name" 
          :typeA="item.typeA" 
          :typeB="item.typeB"
          :status="item.status" 
          :number="item.number" 
          :updated_at="item.updated_at" 
          :id="item.id" 
          :key="item.id" />
  </main>
</template>