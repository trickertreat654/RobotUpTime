import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

export function useInfiniteScroll(propName) {

    const value = () => usePage().props[propName];
    
const items = ref(value().data);

const initialUrl = usePage().url;

const canLoadMoreItems = computed(() => value().next_page_url !== null);

const loadMoreItems = () => {

    if (canLoadMoreItems.next_page_url) return;
    
    router.get(value().next_page_url, {}, {
        preserveState: true,
        preserveScroll: true,
        replace: false,
        
        
        onSuccess: () => {
          console.log(initialUrl)
        //   window.history.replaceState({}, '', initialUrl);
          window.history.replaceState({}, '', initialUrl);
          items.value = [...items.value, ...value().data];
      }
  })
}

return {items,
    loadMoreItems,
    initialUrl,
reset: () => items.value = value().data,
canLoadMoreItems
}
};
