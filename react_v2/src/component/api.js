import axios from 'axios';

export const getEntityList = () => {
    return axios.get('/entity/list')
        .then((res) => res.data)
        .catch((e) => {
            throw e.response.data.content;
        });
};
