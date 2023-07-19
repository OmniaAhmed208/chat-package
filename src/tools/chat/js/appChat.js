const chatButton = document.querySelector('#chatbox__button');
const chatContent = document.querySelector('#chatbox__support');
// const icons = {
//     isClicked: '<img src="./images/icons/chatbox-icon.svg" />',
//     isNotClicked: '<img src="./images/icons/chatbox-icon.svg" />'
// }
// const chatbox = new InteractiveChatbox(chatButton, chatContent, icons);
// chatbox.display();
// chatbox.toggleIcon(false, chatButton);


chatButton.addEventListener('click', function() {
// Toggle the display property of the support element
if (chatContent.style.display === 'none') {
    chatContent.style.display = 'flex';
} else {
    chatContent.style.display = 'none';
}
});