
export default {
    fetch: (team_id) => {
        return axios.get(route('api.bank-accounts.fetch',team.id))
            .then(({data}) => {
                return data;
            })
    }
}
