import modal from '@/components/dy/Modal/modal.js';
export const useEdit = ({ getData }) => {
  const queryParams = ref({
    type: '',
    date: [],
  });

  return { queryParams };
};
