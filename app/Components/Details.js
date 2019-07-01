import React, { Component } from 'react'
import { Text, View,TouchableOpacity,SafeAreaView,AsyncStorage,ScrollView,FlatList , StyleSheet} from 'react-native'
import AnimateLoadingButton from 'react-native-animate-loading-button';
import { FAB } from 'react-native-paper';
import Icon from 'react-native-vector-icons/Ionicons';
import DatePicker from 'react-native-datepicker'
import './Global'
export default class Details extends Component {
  constructor(props){
    super(props)
    this.state = {
      sdate: null,
      edate: null,
      myUser:null,
      details:[],
      percent: null,
    }
  }
  render() {
    return (
      <SafeAreaView style={{flex:1}}>
     
      <View style={{flexDirection: 'row' ,borderBottomWidth:5, borderBottomColor:'#263238' ,paddingTop:10, paddingBottom:10}}>
        <DatePicker
        style={{width: 145,marginLeft:10}}
        date={this.state.sdate}
        mode="date"
        placeholder="Start Date"
        format="YYYY-MM-DD"
        confirmBtnText="Confirm"
        cancelBtnText="Cancel"
        customStyles={{
          dateIcon: {
            position: 'absolute',
            left: 0,
            top: 4,
            marginLeft: 0
          }   
        }}
        onDateChange={(sdate) => {this.setState({sdate: sdate})}}
      />
      <Text style={{fontSize:20,margin:5,color:'#263238'}}>-To-</Text>
      <DatePicker
        style={{width: 145,marginRight:10}}
        date={this.state.edate}
        mode="date"
        placeholder="End Date"
        format="YYYY-MM-DD"
        confirmBtnText="Confirm"
        customStyles={{
          dateIcon: {
            position: 'absolute',
            left: 0,
            top: 4,
            marginLeft: 0
          },
        }}
        onDateChange={(edate) => {this.setState({edate: edate})}}
      />
      <AnimateLoadingButton
        ref={c => (this.loadingButton = c)}
        width={50}
        height={40}
        title="GET"
        titleFontSize={16}
        titleColor="#FFFFFF"
        backgroundColor="#263238"
        onPress={async () => {
          if(this.state.sdate && this.state.edate){
          this.loadingButton.showLoading(true);
          this.setState({
            details:[],
          })
          const  user = await AsyncStorage.getItem('user');
          this.setState({myUser: user});
          var url = global.url + 'detail.php'
          var data = new FormData()
          data.append('username', this.state.myUser)
          data.append('sdate', this.state.sdate)
          data.append('edate' , this.state.edate)
          fetch(url, {
            method: 'POST',
            body: data  
        }).then(response => response.json())
            .then((res) => {
              this.setState({
                percent: JSON.stringify(res.myheader.percent)
              })
              for (var i=0; i < 180; i++) {
                const obj = res[i]
                if(obj != null){
                this.setState({
                  details: [...this.state.details, obj]
                })
              }
              }
            }).catch((error) =>{
              alert('Check the Dates Properly And Try Again');
            });
            this.loadingButton.showLoading(false); 
          }
          else{
            alert('Please Select Dates')
          }  
        }}
      />

      </View>
      <ScrollView>
      <View>
      <FlatList
        style={{ alignSelf: 'stretch' }}
        data={this.state.details}
        extraData={this.state.details}
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
          <View style={{}}>
          <Text style={{fontSize:15}}>Subject : {item.subname}</Text>
          <Text>Date: {item.date} </Text>
          <Text>Time: {item.time}</Text>
          </View>
          </View>
          </View>
          }/>
        
      </View>
      </ScrollView> 
      <FAB style={styles.fab} label={`${this.state.percent}%`} icon="timeline" onPress={() => alert(`Percentage of Attendance For The Selected Dates Is: ${this.state.percent}%`)}/>
      </SafeAreaView>
    )
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
})