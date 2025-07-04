import modal from '@/components/dy/Modal/modal.js';
import EditModal from './EditModal.vue';

import { Modal } from 'ant-design-vue';

export const useEdit = ({ getData }) => {
  const showEditModal = (record) => {
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
        default: () =>
          h(EditModal, {
            record: record,
            onSuccess: () => {
              modal.close();
              getData();
            },
          }),
      }
    );

    modal.open({
      Modal: newModal,
      props: {
        title: `${record?.id ? '编辑' : '添加'}公告`,
        submit: () => {},
      },
    });
  };
  return {
    showEditModal,
  };
};
