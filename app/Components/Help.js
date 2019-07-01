import React, { Component } from 'react';
import { StyleSheet, Text, View, TextInput, KeyboardAvoidingView, Alert, AsyncStorage } from 'react-native';
import AnimateLoadingButton from 'react-native-animate-loading-button';
import publicIP from 'react-native-public-ip';
import './Global'

export default class Help extends Component {

    constructor(props) {
        super(props);
        this.state = {
            husername: '',
            hpassword: '',
            hip:null,
            huid:null
        }
    }
    componentDidMount(){
      publicIP().then(ip => {this.setState({hip : ip})}).catch(error => {console.log(error);})
      setTimeout(this._loadInitialState);  
    }
  
  _loadInitialState = async () => {
      const  huser = await AsyncStorage.getItem('huser');
      const  hpass = await AsyncStorage.getItem('hpass');
      {huser && hpass ? this.setState({husername : huser , hpassword : hpass}) : this.setState({})}
  }
    render() {
        return (
            <KeyboardAvoidingView style={styles.wrapper}>
                <View style={styles.container}>
                    <Text style={styles.header}>Help a Friend</Text>
                    <Text onPress={this._help.bind(this)}>{this.state.husername}</Text> 
                    <TextInput
                        style={styles.textinput} placeholder="Username"
                        onChangeText={(husername) => this.setState({ husername })}
                        underlineColorAndroid='#263238'
                    />
                    <TextInput
                        style={styles.textinput} placeholder="Password"
                        onChangeText={(hpassword) => this.setState({ hpassword })}
                        underlineColorAndroid='#263238'
                        secureTextEntry={true}
                    />
                    <AnimateLoadingButton
                        ref={c => (this.loadingButton = c)}
                        width={300}
                        height={50}
                        title="SUBMIT"
                        titleFontSize={16}
                        titleColor="rgb(255,255,255)"
                        backgroundColor="#263238"
                        borderRadius={4}
                        onPress={this._help.bind(this)}
                    />
                </View>
            </KeyboardAvoidingView>
        );
    }
    
    async _help() {
      const uid = await AsyncStorage.getItem('uid');
      this.setState({huid : uid});
      var url = global.url + 'help.php'
        var data = new FormData()
        data.append('fusername', this.state.husername)
        data.append('fpass', this.state.hpassword)
        data.append('imei', this.state.huid)
        data.append('ipaddr', this.state.hip)
        console.log(this.state.hip)
        console.log(this.state.huid)
        this.loadingButton.showLoading(true);
        fetch(url, {
            method: 'POST',
            body: data  
        }).then(response => response.json())
            .then((res) => {
                this.loadingButton.showLoading(false);
                if(res.message === 'Successfull'){
                  Alert.alert(
                    'Successfull',
                    'Attendance Was Marked Successfully',
                    [
                      {text: 'OK', onPress: () => this.props.navigation.navigate('Help')},
                    ],
                    { cancelable: false }
                  )
                    AsyncStorage.setItem('huser', this.state.husername);
                    AsyncStorage.setItem('hpass', this.state.hpassword);
                }
                else if(res.message === 'Invalid Username' || res.message === "Wrong Password"){
                  Alert.alert(
                    'Try Again',
                    'Check Username and Password',
                    [
                      {text: 'OK', onPress: () => this.props.navigation.navigate('Help')},
                    ],
                    { cancelable: false }
                  )
                }
                else if(res.message === 'Already Marked'){
                  Alert.alert(
                    'Error',
                    'Attendance is Already Marked',
                    [
                      {text: 'OK', onPress: () => this.props.navigation.navigate('Help')},
                    ],
                    { cancelable: false }
                  )
                }
                else if(res.message === 'User Blocked'){
                  Alert.alert(
                    'Blocked',
                    'Your Account is Blocked by Faculty',
                    [
                      {text: 'OK', onPress: () => this.props.navigation.navigate('Help')},
                    ],
                    { cancelable: false }
                  )
                }
                else if(res.message === 'Cannot Verify Main Account'){
                  Alert.alert(
                    'Error',
                    'Mark Attendance of the Singed in Account First',
                    [
                      {text: 'OK', onPress: () => this.props.navigation.navigate('Help')},
                    ],
                    { cancelable: false }
                  )
                }
                else if(res.message === 'Over'){
                  Alert.alert(
                    'Over',
                    'The Attendance Taking Process is Finished',
                    [
                      {text: 'OK', onPress: () => this.props.navigation.navigate('Profile')},
                    ],
                    { cancelable: false }
                  )
                }
              
                else{
                  Alert.alert(
                    'Something Went Wrong',
                    'Check Username and Password or Try Again After Sometime',
                    [
                      {text: 'OK', onPress: () => this.props.navigation.navigate('Help')},
                    ],
                    { cancelable: false }
                  )
                }
            }).catch((error) => {
               alert('Check Internet Connection');
               this.loadingButton.showLoading(false);
            });
    }
}

const styles = StyleSheet.create({
    wrapper: {
        flex: 1,
    },
    container: {
        backgroundColor: '#FFFFFF',
        flex: 1,
        alignItems: 'center',
        justifyContent: 'center',
        paddingLeft: 40,
        paddingRight: 40,
    },
    header: {
      fontSize:30,
        alignSelf: 'center',
        marginBottom: 60,
    },
    textinput: {
        alignSelf: 'stretch',
        padding: 16,
        marginBottom: 20,
    },
   
})