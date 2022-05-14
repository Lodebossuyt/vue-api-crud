<template>
    <div>
        <h2>Articles</h2>
        <form @submit.prevent="addArticle()" action="">
            <div class="form-group mb-2">
                <input v-model="article.title" class="form-control" type="text" placeholder="Title">
            </div>
            <div class="form-group mb-2">
                <textarea v-model="article.body" class="form-control" type="text" placeholder="Body"></textarea>
            </div>
            <button class="btn btn-info mb-2" type="submit">Save</button>
        </form>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li v-bind:class="[{disabled: !pagination.prev_page_url}]" @click="fetchArticles(pagination.prev_page_url)" class="page-item"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item disabled text-dark"><a class="page-link" href="#">Page {{pagination.current_page}} of {{pagination.last_page}}</a></li>
                <li v-bind:class="[{disabled: !pagination.next_page_url}]" @click="fetchArticles(pagination.next_page_url)" class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
        <div v-for="article in articles" :key="article.id"
        class="card card-body mb-2">
            <h3>{{article.title}}</h3>
            <p>{{article.body}}</p>
            <button @click="deleteArticle(article.id)" class="btn btn-danger mb-2">delete</button>
            <button @click="editArticle(article)" class="btn btn-warning mb-2">Edit</button>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                articles: [], //array
                article: {
                    id: '',
                    title: '',
                    body: '',
                }, //object
                article_id: '',
                pagination: {}, //object
                edit: false,
            }
        },
        created(){
            this.fetchArticles();
        },
        methods: {
            fetchArticles(page_url){
                let vm = this;
                page_url = page_url || '/api/articles'
                axios.get(page_url)
                    .then(response => response.data)
                    .then(response => {
                        this.articles = response.data;
                        vm.makePagination(response.meta, response.links);
                    }).catch(err => console.log(err));
            },
            makePagination(meta, links) {
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev
                };
                this.pagination = pagination;
            },
            deleteArticle(id){
                if(confirm('Are You sure?')){
                    axios.delete(`api/article/${id}`)
                        .then(response => alert('Article removed'));
                    this.fetchArticles();
                }
            },
            addArticle(){
                if(this.edit === false){
                    //add
                    axios.post('api/article', this.article)
                        .then(response => {
                            this.article.title = '';
                            this.article.body = '';
                            alert('Article Added');
                            this.fetchArticles();
                        })
                }else{
                    //update
                    axios.put('api/article', this.article)
                        .then(response => {
                            this.article.title = '';
                            this.article.body = '';
                            alert('Article updated');
                            this.fetchArticles();
                        })
                }
            },
            editArticle(article){
                this.edit = true;
                this.article.id = article.id;
                this.article.article_id = article.id;
                this.article.title = article.title;
                this.article.body = article.body;
            }
        }
    }
</script>
