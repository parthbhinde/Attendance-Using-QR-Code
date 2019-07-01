import React, { Component } from 'react'
import {FlatList,Text , View , AsyncStorage , SafeAreaView , StyleSheet ,RefreshControl} from 'react-native'
import Icon from 'react-native-vector-icons/Ionicons';
import { FAB } from 'react-native-paper';
import { ScrollView } from 'react-native-gesture-handler';
import './Global'
export default class Profile extends Component {
  constructor(props) {
    super(props);
    this.state = {
        myPass: null,
        myUser: null,
        att:[],
        name: null,
        rollno: null,
        classname: null,
        refreshing:false,
    }
  }
  componentDidMount() {
    setTimeout(this._loadInitialState);  
}

_loadInitialState = async () => {
  this.setState({refreshing: true,att:[]});
    const  user = await AsyncStorage.getItem('user');
    this.setState({myUser: user});
    const  pass = await AsyncStorage.getItem('pass');
    this.setState({myPass: pass});
    var url = global.url + 'app.php'
    var data = new FormData()
    data.append('username', this.state.myUser)
    data.append('password', this.state.myPass)
    fetch(url, {
      method: 'POST',
      body: data  
  }).then(response => response.json())
      .then((res) => {
        
        if(res.myheader.response === true){
          AsyncStorage.setItem('name', res.myheader.name);
          this.setState({name: res.myheader.name});
          AsyncStorage.setItem('rollno', toString(res.myheader.rollno));
          this.setState({rollno: res.myheader.rollno});
          AsyncStorage.setItem('classname', res.myheader.classname);
          this.setState({classname: res.myheader.classname});
          for (var i=0; i < 6; i++) {
            const obj = res[i]
            this.setState({
              att: [...this.state.att, obj]
            })
          }
        }
        this.setState({refreshing: false});
      })  
}

  render() {
   
    return (
      <SafeAreaView style={{  flex:1}}>
      <View style={{justifyContent:'center' , alignItems:'center' , backgroundColor:'#263238', padding:50}}>
          <Text style={{fontSize:40 , color:'white'}}>{this.state.name}</Text>
          <Text style={{fontSize:20 , color:'white'}}>{this.state.rollno}</Text>
          <Text style={{fontSize:20 , color:'white'}}>{this.state.classname}</Text>
        </View>
        <ScrollView
        refreshControl={
          <RefreshControl
            refreshing={this.state.refreshing}
            onRefresh={this._loadInitialState}
          />
        }>
      <View >
        
      
      <FlatList
        style={{ alignSelf: 'stretch' }}
        data={this.state.att}
        extraData={this.state.att}
        keyExtractor={(item) => item.time}
        renderItem={({ item }) => <View
          style={{
            padding: 10,
            fontWeight: 'bold',
            fontSize: 17,
            color: '#111111',
            
            marginBottom: 0,
            alignSelf: 'stretch',
          }}>
          <View style={{flexDirection: 'row'}}>
          <View style={{alignItems:'flex-start', paddingTop:10, marginLeft:10}}>
          {(item.status == 'Absent' ? <Icon name='md-close' size={40} style={{marginRight:30}}></Icon> : <Icon name='md-checkmark' size={40}style={{marginRight:20}}></Icon>)}
          </View>
          <View style={{alignitems:'flex-end'}}>
          <Text style={{fontSize:15}}>Subject : {item.subname}</Text>
          <Text>Date: {item.date} </Text>
          <Text>Time: {item.time}</Text>
          </View>
          </View>
          </View>
          }/>
        
      </View>
      </ScrollView> 
      <View>
      <FAB style={styles.fab} icon="camera-alt" onPress={() => {this.props.navigation.navigate('Scan')}}/>
      </View>
      
    </SafeAreaView>
    
    );
  }
}
const styles = StyleSheet.create({
  fab: {
    backgroundColor:'#263238',
    position: 'absolute',
    margin: 16,
    right: 0,
    bottom: 0,
  },
  loading: {
    position: 'absolute',
    left: 0,
    right: 0,
    top: 0,
    bottom: 0,
    alignItems: 'center',
    justifyContent: 'center'
  }
})