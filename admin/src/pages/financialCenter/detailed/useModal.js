import modal from '@/components/dy/Modal/modal.js';
import financialCenter from './index.vue';

import { Modal } from 'ant-design-vue';

export const useFinancialCenter = () => {
  const showFinancialModal = (id) => {
    const newModal = h(
      Modal,
      {
        visible: true,
        title: '自定义 Modal',
        onCancel: modal.close,
        footer: null,
        width: '1200px',
        style: { maxHeight: '80vh', overflow: 'auto' },
      },
      {
        default: () => h(financialCenter, { user_id: id }),
      }
    );

    modal.open({
      Modal: newModal,
      props: {
        title: '账变详情',
        submit: () => {},
      },
    });
  };
  return {
    showFinancialModal,
  };
};
