import React, { Component } from 'react';
import {View, Text, FlatList, ActivityIndicator, AsyncStorage} from 'react-native';
import { ListItem, SearchBar } from 'react-native-elements';
import { HeaderPage } from '../../components/global/HeaderPage';
import Colors from '../../assets/styles/Colors';
import { TeamSelectRow } from '../../components/cards/TeamSelectRow';
//https://www.freecodecamp.org/news/how-to-build-a-react-native-flatlist-with-realtime-searching-ability-81ad100f6699/
export class TeamSelect extends Component {
  static navigationOptions = {
    title: 'Pick a User',
}
  constructor(props) {
      console.log("Running Constructor for userPicker.js");
    super(props);

    this.state = {
      loading: false,
      data: [],
      error: null,
    };
    

    this.arrayholder = [];
  }

  componentDidMount() {
      console.log("userPicker.js mounted");
    this.makeRemoteRequest();
  }

  /*makeRemoteRequest = async() => {
    this.setState({ loading: true });
      const userToken = await AsyncStorage.getItem('@token');


      fetch("http://ec2-18-217-231-79.us-east-2.compute.amazonaws.com/users", {
          "method": "GET",
          "headers": {
              "token": userToken
          }
      })
      .then(res => res.json())
      .then(res => {
        this.setState({
          data: res.response,
          error: res.error || null,
          loading: false,
        });
        this.arrayholder = res.response;
        console.log("Data fetched");
      })
      .catch(error => {
        this.setState({ error, loading: false });
      });
  };
  */

  makeRemoteRequest = async() => {
      var testData = [
        
        {
            team: 100,
            rank: 3,
            name: "Wildhats"
        },
        {
            team: 254,
            rank: 1,
            name: "The Cheesy Poofs"
        },
        {
            team: 971,
            rank: 2,
            name: "Spartan Robotics"
        }
    ];
      this.setState({
          data:testData,
      loading: false
    
    });
    this.arrayholder = testData;
  }

  renderSeparator = () => {
    return (
      <View
        style={{
          height: 1,
          width: '100%',
          backgroundColor: '#CED0CE',
          marginLeft: '0%',
        }}
      />
    );

  };

  searchFilterFunction = text => {
    this.setState({
      value: text,
    });

    const newData = this.arrayholder.filter(item => {
        console.info(item);
      const itemData = `${item.team} ${item.name}`.toUpperCase();
      const textData = text.toUpperCase();

      return itemData.indexOf(textData) > -1;
    });
    console.info(newData);
    this.setState({
      data: newData,
    });
  };

  renderHeader = () => {
      console.log("Rendering Header");
    return (
      <SearchBar
        placeholder="Type Here..."
        lightTheme
        round
        onChangeText={text => this.searchFilterFunction(text)}
        autoCorrect={false}
        value={this.state.value}
      />
    );
  };

  render() {
      console.log("Rendering");
    if (this.state.loading) {
      return (
        <HeaderPage title="Loading" bkg={Colors.TeamSelectColor.color}>

        <View style={{ flex: 1, alignItems: 'center', justifyContent: 'center' }}>
          <ActivityIndicator />
        </View>
        </HeaderPage>
      );
    }
    return (
        <HeaderPage title="Teams" bkg={Colors.TeamSelectColor.color}>
            <View style={{ flex: 1 }}>
        <FlatList
          data={this.state.data}
          renderItem={({ item }) => (
            <TeamSelectRow teamInfo ={item} />
          )}
          keyExtractor={item => item.team}
          ItemSeparatorComponent={this.renderSeparator}
          ListHeaderComponent={this.renderHeader}
          
        />
      </View>
        </HeaderPage>
      
    );
  }
}