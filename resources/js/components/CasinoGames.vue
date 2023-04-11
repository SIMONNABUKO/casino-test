<template>
  <div>
    <section id="sidebar">
      <div>
        <h6 class="h-1 border-bottom">{{ selectedCategory }}</h6>
        <ul>
          <li class="nav-item" @click="filterGames('all', 'All')">
            <a
              class="nav-link active"
              href="#tabs-1-1"
              role="tab"
              data-toggle="tab"
              >All</a
            >
          </li>
          <li
            class="nav-item"
            v-for="category in categories"
            :key="category.id"
            @click="filterGames(category.real_id, category.title)"
          >
            <a
              class="nav-link active"
              href="#tabs-1-1"
              role="tab"
              data-toggle="tab"
              >{{ category.title }}</a
            >
          </li>
        </ul>
      </div>
    </section>
    <section id="products">
      <div class="container">
        <div class="row">
          <h3 class="text-center" id="red">{{ selectedCategory }}</h3>
        </div>
        <div class="row">
          <div
            class="col-lg-3 col-sm-4 col-11 offset-sm-0 offset-1"
            v-for="game in filteredGames"
            :key="game.id"
            @click="openGame(game.id)"
            style="cursor: pointer;"
          >
            <div class="card">
              <img
                class="card-img-top"
                :src="`https://test.rentalb.al/frontend/Default/ico/${game.name}.jpg`"
                alt="Card image cap"
              />
              <div class="card-body">
                <p class="card-text">{{ game.title }}</p>
                <span id="teal">{{
                  game.category ? game.category.title : ""
                }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  props: { gameCategories: Array, casinoGames: Array },
  data() {
    return {
      categories: [],
      games: [],
      selectedCategory: "All",
      filteredGames: [],
    };
  },
  methods: {
    filterGames(categoryId, title) {
      console.log(categoryId);
      if (categoryId === "all") {
        this.filteredGames = this.games;
      } else {
        this.filteredGames = this.games.filter(
          (game) => game.category == title
        );
      }
      this.selectedCategory = title;
    },
    openGame(gameId) {
      window.location.href = `/games/${gameId}`;
    },
  },

  mounted() {
    this.categories = JSON.parse(this.gameCategories);
    this.games = JSON.parse(this.casinoGames);
    this.filteredGames = this.games;
    console.log("Component mounted.");
    // console.log("Game categories: ", this.categories);
    // console.log("casino games: ", this.games);
  },
};
</script>
